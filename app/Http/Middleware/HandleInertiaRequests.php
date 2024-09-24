<?php

namespace App\Http\Middleware;

use App\Enum\Lang;
use App\Http\Resources\LanguageResource;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Arr;
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
        }]);
    }
}
