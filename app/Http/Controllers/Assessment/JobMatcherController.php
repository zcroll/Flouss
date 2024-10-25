<?php
namespace App\Http\Controllers\Assessment;

use App\Http\Controllers\Controller;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class JobMatcherController extends Controller
{
    public function matchJobs(Request $request)
    {
        // // Validate the request
        // $validated = $request->validate([
        //     'interest_scores' => 'required|array',
        //     'interest_scores.*' => 'required|integer|min:1|max:5'
        // ]);

        // $interest_scores = $validated['interest_scores'];


         $interest_scores = [
            "Finance" => 2,
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
            "Information technology" => 5,
            "Music" => 2
        ];

        $scriptPath = app_path('/python/test.py');

        // Pass interest_scores to the Python script
        $process = new Process([
            'python3',
            $scriptPath,
            json_encode($interest_scores),
        ]);
        
        try {
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
            Log::info('Python script output', ['output' => $output]);

            $decodedOutput = json_decode($output, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Failed to decode Python script output');
            }

            return response()->json([
                'status' => 'success',
                'data' => $decodedOutput
            ]);

        } catch (\Exception $e) {
            Log::error('Job matching failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process job matching'
            ], 500);
        }
    }
}
