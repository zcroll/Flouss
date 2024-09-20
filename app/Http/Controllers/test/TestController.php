<?php
namespace App\Http\Controllers\test;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Result;
use App\Traits\ArchetypeFinder;
use App\Traits\CalculatesScores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Session;

class TestController extends Controller
{
    use CalculatesScores, ArchetypeFinder;

    public function index()
    {
        $activities = Session::get('activities');
        $currentIndex = Session::get('current_index', 0);
        $responses = Session::get('responses', []);

        // Check if the user already has a result
        $userId = auth()->id();
        $existingResult = Result::where('user_id', $userId)->first();

        if ($existingResult) {
            return to_route('dashboard');
        }

        if (!$activities) {
            $activities = $this->initializeActivities();
            Session::put('activities', $activities);
        }

        if ($currentIndex >= count($activities)) {
            return $this->processResults($responses);
        }

        $formattedActivities = array_map(function ($activity) {
            return [
                'id' => $activity['id'],
                'name' => $activity['name'],
                'category' => $activity['category'],
            ];
        }, $activities);

        ds(['activities' => $formattedActivities]);

        return Inertia::render('Test/ActivityProgress', [
            'activities' => $formattedActivities,
            'totalQuestions' => count($activities),
            'currentIndex' => $currentIndex,
        ]);
    }

    public function submitAnswer(Request $request)
    {
        $responses = $request->input('responses');
        
        $formattedResponses = [];
        foreach ($responses as $response) {
            if (!isset($formattedResponses[$response['category']])) {
                $formattedResponses[$response['category']] = [
                    'score' => 0,
                    'responses' => []
                ];
            }
            $formattedResponses[$response['category']]['score'] += $response['score'] / 100;
            $formattedResponses[$response['category']]['responses'][] = [
                'id' => $response['id'],
                'score' => $response['score'] 
            ];
        }

        ds(['formattedResponses' => $formattedResponses]);

        $result = $this->processResults($formattedResponses);

        return to_route('results')->with('closestJobs', $result);

    }

    
    private function processResults($groupedResponses)
    {
        $hollandScores = $this->calculateHollandScores($groupedResponses);
        $archetype = $this->getArchetypeAndTopScores($hollandScores);
        $closestJobs = $this->matchJobs($hollandScores);
        $result = $this->saveResult($closestJobs, $hollandScores, $archetype);

        return $result;
    }

    private function matchJobs($hollandScores)
    {
        $scriptPath = app_path('/python/test.py');
        $process = new Process(['python3', $scriptPath, json_encode($hollandScores)]);
        $process->run();

        if (!$process->isSuccessful()) {
            Log::error('Python script execution failed', ['error' => $process->getErrorOutput()]);
            throw new ProcessFailedException($process);
        }

        $output = $process->getOutput();
        try {
            return json_decode($output, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            Log::error('Failed to decode JSON from Python script', ['error' => $e->getMessage(), 'output' => $output]);
            throw new \RuntimeException('Failed to process job matching results');
        }
    }

    private function saveResult($closestJobs, $scores, $archetype)
    {
        try {
            return Result::create([
                'user_id' => auth()->id(),
                'scores' => json_encode($scores, JSON_THROW_ON_ERROR),
                'jobs' => json_encode($closestJobs, JSON_THROW_ON_ERROR),
                'highestTwoScores' => $archetype['topTraits'],
                'Archetype' => $archetype['archetypes'],
            ]);
        } catch (\JsonException $e) {
            Log::error('Failed to encode scores or jobs for database storage', ['error' => $e->getMessage()]);
            throw new \RuntimeException('Failed to save assessment results');
        }
    }

    private function initializeActivities(): array
    {
        return $this->fetchActivitiesByType('holland_codes');
    }

    private function fetchActivitiesByType(string $type): array
    {
        $activities = Question::where('type', $type)->get();

        return $activities->map(function ($activity) {
            return [
                'category' => $activity->trait_category,
                'id' => $activity->id,
                'type' => $activity->type,
                'name' => $activity->question_text
            ];
        })->toArray();
    }

    private function groupResponsesByType($responses)
    {
        $groupedResponses = [];
        foreach ($responses as $type => $responseData) {
            $groupedResponses[$type] = $responseData['responses'];
        }
        return $groupedResponses;
    }

    private function calculateHollandScores($groupedResponses)
    {
        $scores = [
            'Realistic' => 0,
            'Investigative' => 0,
            'Artistic' => 0,
            'Social' => 0,
            'Enterprising' => 0,
            'Conventional' => 0
        ];

        foreach ($groupedResponses as $type => $responseData) {
            $scores[$type] = $responseData['score'];
        }

        Log::info('Calculated Holland Scores:', ['scores' => $scores]);

        return $scores;
    }
}
