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

    private function getSessionData()
    {
        return [
            'result_user' => Session::get('result_user'),
            'basic_interest_responses' => Session::get('basic_interest_progress.responses', []),
            'personality_responses' => Session::get('personality', []),
            'holland_codes_progress' => Session::get('holland_codes_progress'),
        ];
    }

    private function validateSessionData($sessionData)
    {
        if (!$sessionData['result_user'] || empty($sessionData['basic_interest_responses'])) {
            throw new \Exception('Missing required session data');
        }

        if (empty($sessionData['holland_codes_progress']['archetypeResults'])) {
            throw new \Exception('Missing archetype results');
        }

        return true;
    }

    private function prepareUserPreferences($sessionData)
    {
        // Default interest scores (from JobMatcherController)
        $defaultInterestScores = [
            "Finance" => 5,
            "Athletics" => 2,
            "Flying" => 4,
            "Law" => 2,
            "Healthcare service" => 2,
            "Physical science" => 1,
            "Nature and agriculture" => 4,
            "Professional advising" => 3,
            "Creative arts" => 2,
            "Culinary arts" => 4,
            "Social sciences" => 3,
            "Beauty & style" => 2,
            "Working with animals" => 1,
            "Creative writing & journalism" => 3,
            "Life science" => 2,
            "Green industry" => 3,
            "Politics" => 4,
            "Engineering" => 4,
            "Sales" => 2,
            "Protective services" => 3,
            "Skilled trades" => 4,
            "Office clerical work" => 2,
            "Mathematics" => 5,
            "Military" => 3,
            "Information technology" => 1,
            "Music" => 2
        ];

        // Default skill scores (from JobMatcherController)
        $defaultSkillScores = [
            486 => 5,  // Mathematical skills
            492 => 4,  // Helping people
            837 => 4,  // Installing equipment
            1308 => 5, // Analytical thinking
            1329 => 4, // Learning new things
            1378 => 4, // Active listening
            1513 => 5, // Science
            1557 => 5, // Critical thinking
            1650 => 4, // Scientific communication
            1687 => 4  // Persuading
        ];

        // Get personality data from session
        $personalityData = $sessionData['personality_responses']['personality'] ?? [];

        // Log raw session data
        Log::info('Raw personality data:', [
            'personality' => $personalityData
        ]);

        // Map the basic interests
        $hollandResponses = $sessionData['basic_interest_responses'];
        $basicInterests = BasicInterest::whereIn('id', array_keys($hollandResponses))
            ->pluck('category', 'id')
            ->toArray();

        $interestScores = [];
        foreach ($hollandResponses as $id => $score) {
            if (isset($basicInterests[$id])) {
                $interestScores[$basicInterests[$id]] = intval($score);
            }
        }

        // Use session data or defaults
        $preferences = [
            'interest_scores' => !empty($interestScores) ? $interestScores : $defaultInterestScores,
            'skill_scores' => !empty($personalityData['skills_preferences']) 
                ? array_map('intval', $personalityData['skills_preferences']) 
                : $defaultSkillScores,
            'must_have_scores' => !empty($personalityData['must_haves'])
                ? array_map('intval', $personalityData['must_haves'])
                : [
                    221 => 4,  // Making decisions independently
                    314 => 3,  // Keeping busy
                    320 => 3,  // Job prestige
                    351 => 4,  // Good working conditions
                    373 => 3,  // Work alone
                    493 => 5,  // Helping people
                    610 => 4,  // Steady employment
                    613 => 4,  // Opportunities for advancement
                    872 => 5,  // Ethical alignment
                    890 => 4,  // Try own ideas
                    973 => 5,  // Fair treatment
                    1194 => 4, // Variety
                    1290 => 5, // Making use of abilities
                    1327 => 4, // Planning own work
                    1367 => 3, // Authority to direct
                    1451 => 4, // Achievement
                    1611 => 5, // Easy co-workers
                    1667 => 5, // Manager support
                    1730 => 4, // Earn money
                    1742 => 4  // Recognition
                ],
            'cant_stand_scores' => !empty($personalityData['cant_stands'])
                ? array_map('intval', $personalityData['cant_stands'])
                : [
                    101 => 5, // Repetitive tasks
                    102 => 3, // High-pressure deadlines
                    103 => 2, // Frequent travel
                    104 => 3, // Irregular hours
                    105 => 4, // Physical labor
                    106 => 4, // High risk environment
                    107 => 2  // Isolation
                ]
        ];

        // Log the prepared preferences
        Log::info('Prepared preferences:', $preferences);

        return $preferences;
    }

    public function checkAllTestsCompleted()
    {
        try {
            // Get all required session data
            $sessionData = $this->getSessionData();
            
            // Log session data
            Log::info('Session data retrieved:', [
                'has_result_user' => !empty($sessionData['result_user']),
                'has_basic_interest' => !empty($sessionData['basic_interest_responses']),
                'has_personality' => !empty($sessionData['personality_responses']),
                'has_holland_codes' => !empty($sessionData['holland_codes_progress'])
            ]);
            
            // Validate session data
            $this->validateSessionData($sessionData);

            DB::beginTransaction();
            
            try {
                // Prepare data for Python script
                $preferences = $this->prepareUserPreferences($sessionData);

                // Log the JSON that will be sent to Python
                $jsonPreferences = json_encode($preferences);
                Log::info('JSON to be sent to Python:', [
                    'json' => $jsonPreferences,
                    'length' => strlen($jsonPreferences)
                ]);

                // Execute Python script
                $pythonPath = 'python3';
                $scriptPath = base_path('app/python/JobMatch.py');
                
                // Log Python execution details
                Log::info('Python execution details:', [
                    'python_path' => $pythonPath,
                    'script_path' => $scriptPath,
                    'working_directory' => base_path('app/python')
                ]);
                
                $process = new Process([
                    $pythonPath,
                    $scriptPath,
                    $jsonPreferences
                ], base_path('app/python'));

                $process->setTimeout(60);
                $process->setIdleTimeout(30);
                $process->enableOutput();

                // Run process and capture output in real-time
                $process->run(function ($type, $buffer) {
                    if (Process::ERR === $type) {
                        Log::error('Python Error Output:', ['buffer' => $buffer]);
                    } else {
                        Log::info('Python Standard Output:', ['buffer' => $buffer]);
                    }
                });

                if (!$process->isSuccessful()) {
                    Log::error('Python script failed', [
                        'error' => $process->getErrorOutput(),
                        'command' => $process->getCommandLine(),
                        'working_directory' => $process->getWorkingDirectory(),
                        'exit_code' => $process->getExitCode(),
                        'output' => $process->getOutput()
                    ]);
                    throw new ProcessFailedException($process);
                }

                $output = $process->getOutput();
                
                // Log raw output
                Log::info('Raw Python output:', ['output' => $output]);
                
                // Clean the output
                $output = trim($output);
                $output = preg_replace('/^[^{]*/m', '', $output);
                $output = preg_replace('/[^}]*$/m', '', $output);
                
                // Log cleaned output
                Log::info('Cleaned Python output:', ['output' => $output]);

                $decodedOutput = json_decode($output, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('JSON decode error:', [
                        'error' => json_last_error_msg(),
                        'raw_output' => $output,
                        'cleaned_output' => $output
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to decode Python script output: ' . json_last_error_msg()
                    ], 500);
                }

                // Process job matches
                $jobMatches = array_map(function($job) {
                    return [
                        'id' => $job['job_id'],
                        'title' => $job['job_title'],
                        'fields' => $job['primary_fields'],
                        'education' => $job['education_level'],
                        'match_score' => $job['match_score'] * 100 . '%'
                    ];
                }, $decodedOutput['job_matches']);

                // Get archetype from session
                $archetype = $sessionData['holland_codes_progress']['archetypeResults'];

                // Get related degrees
                $jobIds = array_column($decodedOutput['job_matches'], 'job_id');
                $degrees = $this->getRelatedDegrees($jobIds);

                // Save results
                $result = new Result();
                $result->user_id = auth()->user()->id;
                $result->scores = $sessionData['result_user']['scores'] ?? [];
                $result->highestTwoScores = $sessionData['result_user']['archtype']['topTraits'] ?? [];
                $result->jobs = $jobMatches;
                $result->degree = json_encode($degrees);
                $result->Archetype = $archetype['archetypes'][0] ?? null;
                $result->save();

                DB::commit();
                
                return request()->wantsJson()
                    ? response()->json(['success' => true, 'message' => 'Results saved successfully'])
                    : to_route('results');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error in try block:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error in checkAllTestsCompleted:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_data' => $sessionData ?? null
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process job matching: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getRelatedDegrees($jobIds)
    {
        return Degree::whereHas('degreeJobsRelation', function($query) use ($jobIds) {
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
    }
}