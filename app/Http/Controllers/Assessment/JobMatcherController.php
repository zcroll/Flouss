<?php
namespace App\Http\Controllers\Assessment;

use App\Http\Controllers\Controller;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

class JobMatcherController extends Controller
{
    public function matchJobs()
    {
        // Define the pool of basic interest IDs
        $basic_interest_ids_pool = [
            4295, 4297, 4300, 4301, 4303, 4305, 4307, 4308, 4309, 4310, 4311, 4313, 4314,
            4316, 4317, 4318, 4319, 4320, 4321, 4323, 4325, 4326, 4327, 4328, 4329,
            4331, 4332,
        ];

        // Randomly select three keys from the pool
        $random_keys = array_rand($basic_interest_ids_pool, 3);

        // Extract the actual basic_interest_ids using the random keys
        $basic_interest_ids = [
            $basic_interest_ids_pool[$random_keys[0]],
            $basic_interest_ids_pool[$random_keys[1]],
            $basic_interest_ids_pool[$random_keys[2]],
        ];
        $holland_scores = [
            'Realistic' => round(mt_rand() / mt_getrandmax(), 2),
            'Investigative' => round(mt_rand() / mt_getrandmax(), 2),
            'Artistic' => round(mt_rand() / mt_getrandmax(), 2),
            'Social' => round(mt_rand() / mt_getrandmax(), 2),
            'Enterprising' => round(mt_rand() / mt_getrandmax(), 2),
            'Conventional' => round(mt_rand() / mt_getrandmax(), 2),
        ];

        ds(['holland_scores' => $holland_scores]);

        // Assign scores based on holland_scores to traits
        $scores = $holland_scores;

        $scriptPath = app_path('/python/test.py');

        // Pass basic_interest_ids and scores to the Python script
        $process = new Process([
            'python3',
            $scriptPath,
            json_encode($basic_interest_ids),
            json_encode($scores)
        ]);
        $process->run();

        if (!$process->isSuccessful()) {
            Log::error('Python script execution failed', [
                'error' => $process->getErrorOutput(),
                'command' => $process->getCommandLine(),
                'working_directory' => $process->getWorkingDirectory(),
            ]);
            throw new ProcessFailedException($process);
        }

        $output = $process->getOutput();
        
        // Log the output of the Python script
        Log::info('Python script output', ['output' => $output]);

        $decodedOutput = json_decode($output, true);

        return response()->json($decodedOutput);
    }
}
