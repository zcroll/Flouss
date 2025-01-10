<?php
namespace App\Http\Controllers\Assessment;

use App\Http\Controllers\Controller;
use App\Traits\ArchetypeFinder;
use Illuminate\Contracts\Session\Session;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class JobMatcherController extends Controller
{
    use ArchetypeFinder;

    private function prepareUserPreferences($request)
    {
        ds(Session('result_user'));
        // Get interest scores from request or use defaults
        $interest_scores = $request->input('interest_scores', [
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
        ]);

        // Get skill scores from request or use defaults
        $skill_scores = $request->input('skill_scores', [
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
        ]);

        // Get must-have scores from request or use defaults
        $must_have_scores = $request->input('must_have_scores', [
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
        ]);

        // Get can't stand scores from request or use defaults
        $cant_stand_scores = $request->input('cant_stand_scores', [
            101 => 5, // Repetitive tasks
            102 => 3, // High-pressure deadlines
            103 => 2, // Frequent travel
            104 => 3, // Irregular hours
            105 => 4, // Physical labor
            106 => 4, // High risk environment
            107 => 2, // Isolation
            108 => 3, // Constant public interaction
            109 => 4, // Rigid hierarchy
            110 => 3  // Frequent relocation
        ]);

        return [
            'interest_scores' => $interest_scores,
            'skill_scores' => $skill_scores,
            'must_have_scores' => $must_have_scores,
            'cant_stand_scores' => $cant_stand_scores
        ];
    }

    public function matchJobs(Request $request)
    {
        try {
            // Prepare user preferences
            $preferences = $this->prepareUserPreferences($request);

            // Get Python script path
            $pythonPath = 'python3';
            $scriptPath = base_path('app/python/JobMatch.py');

            // Create and run process
            $process = new Process([
                $pythonPath,
                $scriptPath,
                json_encode($preferences)
            ]);

            $process->run();

            // Check for process failure
            if (!$process->isSuccessful()) {
                Log::error('Python script execution failed', [
                    'error' => $process->getErrorOutput(),
                    'command' => $process->getCommandLine(),
                    'working_directory' => $process->getWorkingDirectory(),
                ]);
                throw new ProcessFailedException($process);
            }

            // Get and decode output
            $output = $process->getOutput();
            Log::info('Python script output', ['output' => $output]);

            $decodedOutput = json_decode($output, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Failed to decode Python script output');
            }

            // Check for error in Python response
            if (isset($decodedOutput['error'])) {
                throw new \Exception($decodedOutput['error']);
            }

            // Get job matches with IDs
            $jobMatches = array_map(function($job) {
                return [
                    'id' => $job['job_id'],
                    'title' => $job['job_title'],
                    'fields' => $job['primary_fields'],
                    'education' => $job['education_level'],
                    'match_score' => $job['match_score'] * 100 . '%'
                ];
            }, $decodedOutput['job_matches']);

            // Calculate archetype scores
            $score = [
                'Realistic' => round(mt_rand(0, 100) / 100, 2),
                'Investigative' => round(mt_rand(0, 100) / 100, 2),
                'Artistic' => round(mt_rand(0, 100) / 100, 2),
                'Social' => round(mt_rand(0, 100) / 100, 2),
                'Enterprising' => round(mt_rand(0, 100) / 100, 2),
                'Conventional' => round(mt_rand(0, 100) / 100, 2)
            ];

            $archetype = $this->getArchetypeAndTopScores($score);

            // Return simplified response
            return response()->json([
                'status' => 'success',
                'data' => [
                    'jobs' => $jobMatches,
                    'total_matches' => count($jobMatches),
                    'personality' => [
                        'archetype' => $archetype['archetypes'],
                        'dominant_traits' => [
                            $archetype['topTraits']['first_trait'],
                            $archetype['topTraits']['second_trait']
                        ]
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Job matching failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process job matching: ' . $e->getMessage()
            ], 500);
        }
    }
}
