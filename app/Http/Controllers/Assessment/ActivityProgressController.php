<?php
namespace App\Http\Controllers\Assessment;

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

class ActivityProgressController extends Controller
{
    use CalculatesScores, ArchetypeFinder;


    public function index()
    {
        $activities = Session::get('activities');
        $currentIndex = Session::get('current_index', 0);
        $responses = Session::get('responses', []);

        if (!$activities) {
            $activities = $this->initializeActivities();
            Session::put('activities', $activities);
        }

        if ($currentIndex >= count($activities)) {
            return $this->processResults($responses);
        }

        return Inertia::render('ActivityProgress', [
            'activity' => $activities[$currentIndex],
            'totalQuestions' => count($activities),
            'currentIndex' => $currentIndex,
        ]);
    }

    public function submitAnswer(Request $request)
    {
        $activityId = $request->input('activityId');
        $answer = $request->input('answer');

        $responses = Session::get('responses', []);
        $activities = Session::get('activities', []);
        $currentIndex = Session::get('current_index', 0);

        $currentActivity = $activities[$currentIndex];
        
        if ($answer === 'skipped') {
            $responses[$activityId] = [
                'answer' => 'skipped',
                'category' => $currentActivity['category'],
                'type' => $currentActivity['type'],
            ];
        } else {
            $responses[$activityId] = [
                'answer' => $answer,
                'category' => $currentActivity['category'],
                'type' => $currentActivity['type'],
            ];
        }
        
        Session::put('responses', $responses);

        $nextIndex = $currentIndex + 1;
        Session::put('current_index', $nextIndex);

        if ($nextIndex >= count($activities)) {
            return $this->processResults($responses);
        }

        return redirect()->route('activity.index');
    }

    public function previousActivity()
    {
        $currentIndex = Session::get('current_index', 0);
        
        if ($currentIndex > 0) {
            $currentIndex--;
            Session::put('current_index', $currentIndex);
            
            $responses = Session::get('responses', []);
            array_pop($responses);
            Session::put('responses', $responses);
        }

        $activities = Session::get('activities');

        return Inertia::render('ActivityProgress', [
            'activity' => $activities[$currentIndex],
            'totalQuestions' => count($activities),
            'currentIndex' => $currentIndex,
        ]);
    }

    private function processResults($responses)
    {
        $validResponses = array_filter($responses, function($response) {
            return $response['answer'] !== 'skipped';
        });

        $hollandScores = $this->calculateHollandScores($validResponses);
        $archetype = $this->getArchetypeAndTopScores($hollandScores);
        $closestJobs = $this->matchJobs($hollandScores);
        $result = $this->saveResult($closestJobs, $hollandScores, $archetype);

        $formattedClosestJobs = array_map(function ($job) {
            return [
                'title' => $job['job_title'] ?? 'Unknown Job',
                'match_percentage' => round($job['match_percentage'] ?? 0, 2),
            ];
        }, $closestJobs);

        Session::forget(['responses', 'current_index', 'activities']);

        return to_route('results')->with('closestJobs', $formattedClosestJobs);
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
}
