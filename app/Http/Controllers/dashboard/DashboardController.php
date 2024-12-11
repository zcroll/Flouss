<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Favorite;
use App\Models\JobInfo;
use App\Models\Result;
use App\Models\ChatHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();

            // Simulate an error for testing
            if (!$user) {
                throw new \Exception('User not found');
            }

            $favoriteJobs = DB::table('favorites')
                ->where('favorites.user_id', $user->id)
                ->where('favorites.favoritable_type', 'App\Models\JobInfo')
                ->join('job_infos', 'favorites.favoritable_id', '=', 'job_infos.id')
                ->select([
                    'favorites.id',
                    'job_infos.name',
                    'job_infos.slug',
                    'job_infos.image'
                ])
                ->limit(6)
                ->get();

            $favoriteDegrees = DB::table('favorites')
                ->where('favorites.user_id', $user->id)
                ->where('favorites.favoritable_type', 'App\Models\Degree')
                ->join('degrees', 'favorites.favoritable_id', '=', 'degrees.id')
                ->select([
                    'favorites.id',
                    'degrees.name',
                    'degrees.image'
                ])
                ->get();

            $hasResult = $user->hasResult();

            $archetype = null;
            $archetypeDiscovery = null;
            $topTraits = [];
            $topJobs = [];

            if ($hasResult) {
                $result = Result::where('user_id', $user->id)->latest()->first();

                if (!empty($result->Archetype)) {
                    $archetype = $result->Archetype;
                    $archetypeDiscovery = DB::table('archetype_discoveries')
                        ->where('slug', '=', strtolower($archetype[0]))
                        ->select(['slug', 'rarity_string', 'rationale', 'scales'])
                        ->first();
                }
                   // Get top 2 traits from scores
                $topTraits = [];
                if (!empty($result->scores)) {
                    $scores = is_array($result->scores) ? $result->scores : json_decode($result->scores, true);
                    arsort($scores);
                    $topTraits = array_slice($scores, 0, 2, true);
                }

                ds(['topTraits'=>$topTraits]);


             

                // if (!empty($result->jobs)) {
                //     $decodedJobs = json_decode($result->jobs, true, 512, JSON_THROW_ON_ERROR);
                    
                //     if (isset($decodedJobs['job_matches'])) {
                //         $jobsCollection = collect($decodedJobs['job_matches']);
                //         $jobIds = $jobsCollection->pluck('job_id')->toArray();

                //         $topJobs = JobInfo::whereIn('id', $jobIds)
                //             ->select('id', 'name', 'slug', 'image')
                //             ->limit(6)
                //             ->get();
                //     }
                // }
            }

            $chatHistory = ChatHistory::where('user_id', $user->id)
                ->orderBy('created_at', 'asc')
                ->get()
                ->flatMap(function ($chat) {
                    return [
                        [
                            'role' => 'user',
                            'content' => $chat->message,
                            'timestamp' => $chat->created_at->format('H:i:s')
                        ],
                        [
                            'role' => 'assistant',
                            'content' => $chat->response,
                            'timestamp' => $chat->created_at->format('H:i:s')
                        ]
                    ];
                })
                ->values()
                ->all();

            $predefinedQuestions = [
                [
                    'id' => 1,
                    'category' => __('dashboard.career_path'),
                    'questions' => [
                        __('dashboard.career_path_q1'),
                        __('dashboard.career_path_q2'), 
                        __('dashboard.career_path_q3')
                    ]
                ],
                [
                    'id' => 2,
                    'category' => __('dashboard.education'),
                    'questions' => [
                        __('dashboard.education_q1'),
                        __('dashboard.education_q2'),
                        __('dashboard.education_q3')
                    ]
                ],
                [
                    'id' => 3,
                    'category' => __('dashboard.skills_development'),
                    'questions' => [
                        __('dashboard.skills_q1'),
                        __('dashboard.skills_q2'),
                        __('dashboard.skills_q3')
                    ]
                ]
            ];

            // ds(['chathistory'=>$chatHistory->toArray()]);
ds(['archetype'=>$archetypeDiscovery]);
            return Inertia::render('Dashboard', [
                'hasResult' => $hasResult,
                'favoriteJobs' => $favoriteJobs,
                'favoriteDegrees' => $favoriteDegrees,
                'archetype' => $archetypeDiscovery,
                'topTraits' => $topTraits,
                'topJobs' => $topJobs,
                'chatHistory' => $chatHistory,
                'predefinedQuestions' => $predefinedQuestions
            ])->with('success', 'Welcome to your dashboard!');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load dashboard data.');
        }
    }

    // Add a test method to trigger different messages
    public function testMessages()
    {
        // Test different types of messages
        return redirect()->route('dashboard')
            ->with('success', 'This is a success message!')
            ->with('error', 'This is an error message!')
            ->with('message', 'This is an info message!');
    }

    public function testFetch()
    {
        try {
            // Simulate different scenarios
            $scenario = request('scenario', 'success');
            
            switch ($scenario) {
                case 'error':
                    throw new \Exception('Failed to fetch data');
                    
                case 'unauthorized':
                    abort(403, 'You are not authorized to access this resource');
                    
                case 'not_found':
                    abort(404, 'Resource not found');
                    
                case 'validation':
                    return back()->withErrors([
                        'field' => 'Invalid input'
                    ])->with('error', 'Validation failed');
                    
                default:
                    // Return success response through Inertia
                    return Inertia::render('Dashboard', [
                        'testData' => [
                            'status' => 'success',
                            'message' => 'Data fetched successfully',
                            'data' => [
                                'id' => 1,
                                'name' => 'Test Data',
                                'timestamp' => now()->toDateTimeString()
                            ]
                        ]
                    ])->with('success', 'Data fetched successfully');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
