<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\ItemSet;
use App\Models\QuestionTrait;
use App\Models\Result;
use App\Models\BasicInterest;
use App\Models\Degree;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\ArchetypeFinder;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ResultTest extends Controller
{
    use ArchetypeFinder;

    public function checkAllTestsCompleted()
    {
        try {
            $resultUser = Session::get('result_user');
            $hollandResponses = Session::get('basic_interest_progress')['responses'] ?? [];
            $personalityResponses = Session::get('personality') ?? [];
            $hollandCodesProgress = Session::get('holland_codes_progress');
            
            if (!$resultUser || !$hollandResponses) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing required session data'
                ], 400);
            }

            DB::beginTransaction();
            
            try {
                $basicInterests = BasicInterest::whereIn('id', array_keys($hollandResponses))
                    ->pluck('category', 'id')
                    ->toArray();

                $interestScores = [];
                foreach ($hollandResponses as $id => $score) {
                    if (isset($basicInterests[$id])) {
                        $interestScores[$basicInterests[$id]] = $score;
                    }
                }

                // Prepare data for Python script
                $preferences = [
                    'interest_scores' => $interestScores,
                    'skill_scores' => $personalityResponses['skills_preferences'] ?? [],
                    'must_have_scores' => $personalityResponses['must_haves'] ?? [],
                    'cant_stand_scores' => $personalityResponses['cant_stands'] ?? []
                ];

                // Call Python script
                $pythonPath = 'python3';
                $scriptPath = base_path('app/python/JobMatch.py');

                $process = new Process([
                    $pythonPath,
                    $scriptPath,
                    json_encode($preferences)
                ]);

                $process->run();

                if (!$process->isSuccessful()) {
                    Log::error('Python script execution failed', [
                        'error' => $process->getErrorOutput(),
                        'command' => $process->getCommandLine()
                    ]);
                    throw new ProcessFailedException($process);
                }

                $output = $process->getOutput();
                $decodedOutput = json_decode($output, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Failed to decode Python script output');
                }

                if (isset($decodedOutput['error'])) {
                    throw new \Exception($decodedOutput['error']);
                }

                $archetype = $hollandCodesProgress['archetypeResults'];

                // Get job IDs from matches
                $jobIds = array_column($decodedOutput['job_matches'], 'job_id');

                // Get related degrees
                $degrees = Degree::whereHas('degreeJobsRelation', function($query) use ($jobIds) {
                    $query->whereIn('job_id', $jobIds);
                })
                ->select('id', 'name', 'slug', 'degree_level', 'salary', 'image')
                ->limit(20)
                ->get()
                ->map(function($degree) {
                    return [        
                        'id' => $degree->id,
                        'name' => $degree->name,
                        'slug' => $degree->slug,
                        'degree_level' => $degree->degree_level,
                        'salary' => $degree->salary,
                        'image' => $degree->image
                    ];
                })
                ->toArray();

                // Store results in database
                $result = new Result();
                $result->user_id = auth()->id();
                $result->scores = $resultUser['scores'] ?? [];
                $result->highestTwoScores = $resultUser['archtype']['topTraits'] ?? [];
                $result->jobs = $resultUser['jobs'] ?? [];
                $result->degree = json_encode($degrees);
                $result->Archetype = $archetype['archetypes'][0] ?? null;
                $result->save();

                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Results saved successfully',
                    'redirect' => route('results')
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error in checkAllTestsCompleted:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_data' => $resultUser ?? null
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process job matching: ' . $e->getMessage()
            ], 500);
        }
    }
}