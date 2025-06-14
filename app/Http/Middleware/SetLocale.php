?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        app()->setLocale($request->segment(1));

        URL::defaults(['locale' => $request->segment(1)]);

        $response = $next($request);

        // Check if the response is an instance of Symfony Response
        if ($response instanceof Response) {
            $content = $response->getContent();
            if ($content !== '') {
                $response->setContent((string) $response->getContent());
            }
        }

        return $response;
    }
}
