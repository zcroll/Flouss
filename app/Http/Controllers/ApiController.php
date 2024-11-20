<?php

namespace App\Http\Controllers;

use OpenAI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Result;
use App\Http\Resources\ResultResource;
use App\Models\ArchetypeCareer;
use App\Models\ChatHistory;
use Illuminate\Support\Facades\DB;


class ApiController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = OpenAI::factory()
            ->withApiKey(env('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'))
            ->withBaseUri('https://models.inference.ai.azure.com')
            ->make();
    }

    public function sendMessage(Request $request)
    {
        Log::info('Received request:', $request->all());

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        // Get user's assessment results for context
        $userId = Auth::id();
        $firstScore = ResultResource::collection(Result::with('user')->where('user_id', $userId)->latest()->get())->first();

        $systemContext = 'You are a helpful AI assistant focused on career guidance and professional development. You have access to the user\'s assessment results, personality archetype, and career matches. Use this information to provide personalized advice and insights.

Format your responses in a clean, organized way using emojis at the start of each point. Present career information like this example:

🧁 Baker
Learn baking techniques through classes
Practice recipes at home
Join baking communities

like this.

Keep explanations simple and direct, using emojis to visually separate topics. Avoid using any special formatting characters like #, **, or -. Each point should be on a new line with just the emoji at the start.

When giving advice:
- Consider their personality archetype and traits
- Reference their top career matches
- Provide actionable steps based on their profile
- Tailor suggestions to their strengths

Show all the data provided in the context and use it to personalize your responses.';

        if ($firstScore) {
            $archetype = $firstScore->Archetype ?? null;
            $jobs = null;
            $jobMatches = [];
            $scales = null;

            if (!empty($firstScore->jobs)) {
                $decodedJobs = json_decode($firstScore->jobs, true, 512, JSON_THROW_ON_ERROR);
                if (isset($decodedJobs['job_matches'])) {
                    $jobMatches = collect($decodedJobs['job_matches'])
                        ->take(3)
                        ->pluck('job_title')
                        ->toArray();
                }
            }

            $Archetype = DB::table('persona')->where('name', $archetype[0] ?? '')->first();

            if (!empty($jobMatches)) {
                $systemContext .= " Based on their assessment, their top career matches include: " . implode(", ", $jobMatches) . ".";
            }

            if ($Archetype) {
                $systemContext .= " Their personality archetype is {$Archetype->name}.";

                if (!empty($Archetype->scales)) {
                    $scales = json_decode($Archetype->scales, true);
                    $systemContext .= " Their key personality traits are:";
                    foreach ($scales as $scale) {
                        $systemContext .= " {$scale['trait']}: {$scale['score']}%,";
                    }
                    $systemContext = rtrim($systemContext, ',') . ".";
                }

                if (!empty($Archetype->description)) {
                    $systemContext .= " {$Archetype->description}";
                }
            }
        }

        try {
            $response = $this->client->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => $systemContext],
                    ['role' => 'user', 'content' => $validated['message']],
                ],
            ]);

            Log::info('OpenAI response:', (array) $response);

            $aiResponse = $response->choices[0]->message->content;

            // Store the chat history
           $chatHistory = ChatHistory::create([
                'user_id' => $userId,
                'message' => $validated['message'],
                'response' => $aiResponse
            ]);
            ds($chatHistory);

            return response()->json([
                'aiMessage' => $aiResponse,
                'success' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('OpenAI request failed:', ['error' => $e->getMessage()]);

            return response()->json([
                'aiMessage' => 'Failed to process your request. Please try again.',
                'success' => false,
            ], 500);
        }
    }
}
