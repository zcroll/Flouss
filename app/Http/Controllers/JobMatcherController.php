<?php
namespace App\Http\Controllers;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Http\Request;

class JobMatcherController extends Controller
{
    public function matchJobs()
    {
        $scriptPath = app_path('/python/test.py');

        $process = new Process(['python3', $scriptPath]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $output = $process->getOutput();
            ds($output);
        return response()->json([
            'success' => true,
            'output' => $output,
        ]);
    }
}
