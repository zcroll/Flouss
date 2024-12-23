<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Result;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserResult
{
    /**
     * Protected routes that should redirect if user has results
     */
    private const PROTECTED_ROUTES = [
        'holland-codes.index',
        'basic-interests.index',
        'degree-assessment.index'
    ];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldCheckResult($request) && $this->userHasResult()) {
            return to_route('dashboard');
        }

        return $next($request);
    }

    /**
     * Determine if the current route should be checked for results
     *
     * @param Request $request
     * @return bool
     */
    private function shouldCheckResult(Request $request): bool
    {
        return Auth::check() && 
               in_array($request->route()->getName(), self::PROTECTED_ROUTES, true);
    }

    /**
     * Check if the authenticated user has any test results
     *
     * @return bool
     */
    private function userHasResult(): bool
    {
        return Result::where('user_id', Auth::id())->exists();
    }
} 