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
use App\Models\JobInfo;
use App\Models\Degree;
use App\Models\ArchetypeDiscovery;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = OpenAI::factory()
            ->withApiKey('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')
            ->withBaseUri('https://models.inference.ai.azure.com')
            ->make();
    }

    protected function getLocalizedColumn($model, $baseColumn)
    {
        $locale = app()->getLocale();
        $localizedColumn = $locale === 'fr' ? $baseColumn . '_fr' : $baseColumn;

        return $model->$localizedColumn ?? $model->$baseColumn;
    }

    public function getWelcomeMessage()
    {
        $user = Auth::user();
        return app()->getLocale() === 'fr' ? 
            "👋 Bonjour {$user->name}! Comment puis-je vous aider aujourd'hui avec votre développement de carrière?" :
            "👋 Hello {$user->name}! How can I help you with your career development today?";
    }

    public function getInitialMessage()
    {
        $userId = Auth::id();
        $welcomeMessage = $this->getWelcomeMessage();

        ds($welcomeMessage);
        
        // Store welcome message in chat history
        ChatHistory::create([
            'user_id' => $userId,
            'message' => 'SYSTEM_WELCOME',
            'response' => $welcomeMessage
        ]);

        return response()->json([
            'aiMessage' => $welcomeMessage,
            'success' => true,
        ]);
    }

    public function sendMessage(Request $request)
    {
        Log::info('Received request:', $request->all());

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        // Get user's assessment results for context
        $userId = Auth::id();
        $user = Auth::user();
        $firstScore = ResultResource::collection(Result::with('user')->where('user_id', $userId)->latest()->get())->first();

        // Check if user has taken the personality test
        $noTestMessage = app()->getLocale() === 'fr' ? 
            "Je remarque que vous n'avez pas encore passé notre test de personnalité. Pour vous donner des conseils plus personnalisés sur votre carrière, je vous encourage vivement à prendre quelques minutes pour compléter le test. Cela me permettra de mieux comprendre vos forces et vos préférences professionnelles. En attendant, je ferai de mon mieux pour vous aider avec des conseils généraux. Souhaitez-vous que je vous explique comment accéder au test?" :
            "I notice you haven't taken our personality test yet. To provide you with more personalized career advice, I strongly encourage you to take a few minutes to complete the test. This will help me better understand your strengths and career preferences. In the meantime, I'll do my best to help you with general advice. Would you like me to explain how to access the test?";

        $systemContext = app()->getLocale() === 'fr' ? 
            "Bonjour {$user->name}! Je suis votre assistant IA personnel axé sur l'orientation professionnelle et le développement professionnel. " . (!$firstScore ? $noTestMessage : "Je suis là pour vous aider avec des conseils personnalisés basés sur vos résultats d'évaluation, votre archétype de personnalité et vos correspondances professionnelles.") . "

Formatez vos réponses de manière claire et organisée en utilisant des émojis au début de chaque point. Présentez les informations sur la carrière comme dans cet exemple:

🧁 Boulanger
Apprendre les techniques de boulangerie à travers des cours
Pratiquer des recettes à la maison
Rejoindre des communautés de boulangers

comme ceci.

Gardez les explications simples et directes, en utilisant des émojis pour séparer visuellement les sujets. Évitez d'utiliser des caractères de formatage spéciaux comme #, **, ou -. Chaque point doit être sur une nouvelle ligne avec juste l'emoji au début." :
            
            "Hello {$user->name}! I'm your personal AI assistant focused on career guidance and professional development. " . (!$firstScore ? $noTestMessage : "I'm here to help you with personalized advice based on your assessment results, personality archetype, and career matches.") . "

Format your responses in a clean, organized way using emojis at the start of each point. Present career information like this example:

🧁 Baker
Learn baking techniques through classes
Practice recipes at home
Join baking communities

like this.

Keep explanations simple and direct, using emojis to visually separate topics. Avoid using any special formatting characters like #, **, or -. Each point should be on a new line with just the emoji at the start.";

        if ($firstScore) {
            $archetype = $firstScore->Archetype ?? null;

            // Transform jobs data with localization
            $jobs = !empty($firstScore->jobs) 
                ? (is_string($firstScore->jobs) 
                    ? json_decode($firstScore->jobs, true, 512, JSON_THROW_ON_ERROR) 
                    : $firstScore->jobs)
                : null;

            if ($jobs) {
                $jobs = collect($jobs)->map(function ($job) {
                    $jobModel = JobInfo::find($job['id']);
                    if ($jobModel) {
                        $job['name'] = $this->getLocalizedColumn($jobModel, 'name');
                        $job['description'] = $this->getLocalizedColumn($jobModel, 'description');
                    }
                    return $job;
                })->toArray();
            }

            // Transform degrees data with localization
            $degrees = !empty($firstScore->degree)
                ? (is_string($firstScore->degree) 
                    ? json_decode($firstScore->degree, true, 512, JSON_THROW_ON_ERROR) 
                    : $firstScore->degree)
                : null;

            if ($degrees) {
                $degrees = collect($degrees)->map(function ($degree) {
                    $degreeModel = Degree::find($degree['id']);
                    if ($degreeModel) {
                        $degree['name'] = $this->getLocalizedColumn($degreeModel, 'name');
                        $degree['description'] = $this->getLocalizedColumn($degreeModel, 'description');
                    }
                    return $degree;
                })->toArray();
            }

            $Archetype = DB::table('persona')->where('name', $archetype[0] ?? '')->first();
            $archetypeDiscovery = ArchetypeDiscovery::where('slug', '=', strtolower($archetype[0] ?? ''))->first();

            if ($jobs) {
                $systemContext .= app()->getLocale() === 'fr' ? 
                    " Vos correspondances professionnelles recommandées sont: " :
                    " Your recommended job matches are: ";
                foreach ($jobs as $job) {
                    $systemContext .= "{$job['name']}, ";
                }
                $systemContext = rtrim($systemContext, ', ') . ".";
            }

            if ($degrees) {
                $systemContext .= app()->getLocale() === 'fr' ? 
                    " Les diplômes recommandés sont: " :
                    " Recommended degrees are: ";
                foreach ($degrees as $degree) {
                    $systemContext .= "{$degree['name']}, ";
                }
                $systemContext = rtrim($systemContext, ', ') . ".";
            }

            if ($Archetype) {
                $systemContext .= app()->getLocale() === 'fr' ? 
                    " Votre archétype de personnalité est {$Archetype->name}." :
                    " Your personality archetype is {$Archetype->name}.";

                if (!empty($Archetype->scales)) {
                    $scales = json_decode($Archetype->scales, true);
                    $systemContext .= app()->getLocale() === 'fr' ? 
                        " Vos principaux traits de personnalité sont:" :
                        " Your key personality traits are:";
                    foreach ($scales as $scale) {
                        $systemContext .= " {$scale['trait']}: {$scale['score']}%,";
                    }
                    $systemContext = rtrim($systemContext, ',') . ".";
                }

                if ($archetypeDiscovery) {
                    $systemContext .= " " . $this->getLocalizedColumn($archetypeDiscovery, 'verbose_description');
                }
            }
        }

        try {
            $messages = [
                ['role' => 'system', 'content' => $systemContext],
                ['role' => 'user', 'content' => $validated['message']],
            ];

            $response = $this->client->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => $messages,
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
            ds($aiResponse);

            return response()->json([
                'aiMessage' => $aiResponse,
                'success' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('OpenAI request failed:', ['error' => $e->getMessage()]);

            return response()->json([
                'aiMessage' => app()->getLocale() === 'fr' ? 
                    'Échec du traitement de votre demande. Veuillez réessayer.' :
                    'Failed to process your request. Please try again.',
                'success' => false,
            ], 500);
        }
    }
}
