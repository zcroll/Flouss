<?php

namespace App\Http\Middleware;

use App\Enum\Lang;
use App\Http\Resources\LanguageResource;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Arr;
use App\Models\Result;
use Illuminate\Support\Facades\Log;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            // 'availableLocales' => config('app.available_locales'),
            'language' => app()->getLocale(),
            'languages' => LanguageResource::collection(Lang::cases()),
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'translations' => function() {
                return cache()->rememberForever('translations' . app()->getLocale(), function() {

                
                return collect(File::allFiles(base_path('lang/' . app()->getLocale())))
                    ->flatMap(function ($file) {

                        return Arr::dot(
                          File::getRequire($file->getRealPath()),
                        $filename = $file->getBasename('.' . $file->getExtension()) . '.' 


                        );
                    });
                   
            });
        },
            // Auth user data
            'auth' => [
                'user' => fn () => $request->user()
                    ? array_merge(
                        $request->user()->only('id', 'name', 'email'),
                        [
                            'archetype' => tap($request->user()
                                ? Result::where('user_id', $request->user()->id)
                                    ->latest()
                                    ->first()?->Archetype
                                : null, function($archetype) {
                                    Log::info('User archetype:', ['archetype' => $archetype]);
                                })
                        ]
                    )
                    : null,
            ],
            
            // Flash messages
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'error' => fn () => $request->session()->get('error'),
                'success' => fn () => $request->session()->get('success'),
            ],
        ]);
    }
}
