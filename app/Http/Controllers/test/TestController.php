<?php

namespace App\Http\Controllers\test;

use App\Http\Controllers\Controller;
use App\Http\Resources\Test\ItemSetResource;
use App\Models\ItemSet;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestController extends Controller
{
    private $testSteps = [
        1 => [
            'id' => 1,
            'title' => 'Personality Archetype',
            'time' => 10,
            'description' => ['Discover your personality type and work preferences'],
            'route' => 'personality-archetype'
        ],
        2 => [
            'id' => 2, 
            'title' => 'Career Matches',
            'time' => 5,
            'description' => ['See which careers match your personality'],
            'route' => 'career-matches'
        ],
        11 => [
            'id' => 11,
            'title' => 'Degree Matches',
            'time' => 5,
            'description' => ['Find degrees that align with your career goals'],
            'route' => 'degree-matches'
        ],
        10 => [
            'id' => 10,
            'title' => 'Final Results',
            'time' => 5,
            'description' => ['Review your complete assessment results'],
            'route' => 'final-results'
        ]
    ];

    public function index()
    {
        $steps = collect($this->testSteps)->map(function($step) {
            return array_merge($step, [
                'status' => $this->getStepStatus($step['id']),
                'progress' => $this->calculateProgress($step['id'])
            ]);
        });

        return Inertia::render('Test/MainTest', [
            'steps' => $steps,
            'currentStep' => $this->getCurrentStep(),
            'totalTime' => $steps->sum('time'),
        ]);
    }
    public function personalityArchetype()
    {
        $itemSet = ItemSet::with(['items.optionSet.options'])
            ->where('id', 1)
            ->firstOrFail();


        return $itemSet;

      
    }

    public function careerMatches()
    {
        return Inertia::render('Test/MainTest', [
            'testStage' => 'career_matches',
            'progress' => $this->calculateProgress(2)
        ]);
    }

    public function degreeMatches()
    {
        return Inertia::render('Test/MainTest', [
            'testStage' => 'degree_matches', 
            'progress' => $this->calculateProgress(11)
        ]);
    }

    public function finalResults()
    {
        return Inertia::render('Test/MainTest', [
            'testStage' => 'final_results',
            'progress' => $this->calculateProgress(10)
        ]);
    }

    private function getStepStatus($stepId)
    {
        $completedSteps = session('completed_steps', []);
        $currentStep = session('current_step', 1);

        if (in_array($stepId, $completedSteps)) {
            return 'completed';
        }

        if ($stepId === $currentStep) {
            return 'inprogress';
        }

        return 'notstarted';
    }

    private function calculateProgress($stepId)
    {
        $progress = session("step_{$stepId}_progress", 0.0);
        return (float) $progress;
    }

    private function getCurrentStep()
    {
        return session('current_step', 1);
    }

    public function updateProgress(Request $request)
    {
        $validated = $request->validate([
            'step_id' => 'required|integer',
            'progress' => 'required|numeric|min:0|max:100',
        ]);

        $stepId = $validated['step_id'];
        $progress = $validated['progress'];

        // Store progress in session
        session(["step_{$stepId}_progress" => $progress]);

        if ($progress >= 100) {
            $completedSteps = session('completed_steps', []);
            $completedSteps[] = $stepId;
            session(['completed_steps' => array_unique($completedSteps)]);

            // Move to next step
            $nextStep = $this->getNextStep($stepId);
            if ($nextStep) {
                session(['current_step' => $nextStep]);
            }
        }

       return to_route('test.index');
    }

    private function getNextStep($currentStepId)
    {
        $stepSequence = array_keys($this->testSteps);
        $currentIndex = array_search($currentStepId, $stepSequence);
        
        if ($currentIndex !== false && isset($stepSequence[$currentIndex + 1])) {
            return $stepSequence[$currentIndex + 1];
        }

        return null;
    }

    public function storeAnswer(Request $request)
    {
        $validated = $request->validate([
            'itemId' => 'required|integer',
            'type' => 'required|string|in:answered,skipped',
            'answer' => 'nullable|numeric',
            'testStage' => 'required|string'
        ]);

        // Get existing answers from session or initialize empty array
        $answers = session()->get('test_answers', []);
        
        $answers[$validated['itemId']] = [
            'type' => $validated['type'],
            'answer' => $validated['answer'],
            'timestamp' => now()->timestamp
        ];

        // Store answers back in session
        session()->put('test_answers', $answers);

        // Calculate progress
        $itemSet = ItemSet::with(['items'])->where('id', 1)->first();
        $totalItems = $itemSet->items->count();
        $answeredItems = count($answers);
        $progress = ($answeredItems / $totalItems) * 100;

        // Update progress in session
        session(["step_1_progress" => $progress]);

        // Get next unanswered question
        $nextItem = $itemSet->items()
            ->whereNotIn('id', array_keys($answers))
            ->first();

        if ($nextItem) {
            return response()->json([
                'success' => true,
                'nextItem' => $nextItem->load('optionSet.options'),
                'progress' => $progress
            ]);
        }

        // If no more questions, mark step as completed
        if ($progress >= 100) {
            $completedSteps = session('completed_steps', []);
            $completedSteps[] = 1; // 1 is the ID for personality archetype step
            session(['completed_steps' => array_unique($completedSteps)]);
            session(['current_step' => 2]); // Move to next step
        }

      return to_route('test.index');
    }
}
