<?php
namespace App\Http\Controllers\Assessment;

use App\Http\Controllers\Controller;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class JobMatcherController extends Controller
{
    public function matchJobs()
    {
        $targetHolland = [
            'Realistic' => 0.20,
            'Investigative' => 0.43,
            'Artistic' => 0.57,
            'Social' => 0.23,
            'Enterprising' => 0.47,
            'Conventional' => 0.33,
        ];

        $targetBigFive = [
            'Openness' => 0.22,
            'Conscientiousness' => 0.83,
            'Extraversion' => 0.35,
            'Agreeableness' => 0.41,
            'Social Responsibility' => 0.64,
        ];

        $scriptPath = app_path('/python/test.py');

        $process = new Process(['python3', $scriptPath, json_encode($targetHolland), json_encode($targetBigFive)]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $output = $process->getOutput();

        return response()->json([
            'success' => true,
            'output' => $output,
        ]);
    }
}
