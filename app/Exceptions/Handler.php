<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Inertia\Inertia;

class Handler extends ExceptionHandler
{
    /**
     * A list of error messages
     *
     * @var array<int, string>
     */
    protected $messages = [
        500 => 'Something went wrong',
        503 => 'Service unavailable',
        404 => 'Not found',
        403 => 'Not authorized',
    ];

    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);
        $status = $response->getStatusCode();

        // Keep default error handling in local/testing
        if (app()->environment(['local', 'testing'])) {
            return $response;
        }

        if (!array_key_exists($status, $this->messages)) {
            return $response;
        }

        // Handle non-GET requests with flash messages
        if (!$request->isMethod('GET')) {
            return back()
                ->setStatusCode($status)
                ->with('error', $this->messages[$status]);
        }

        // Return Inertia error page for GET requests
        return Inertia::render('Error/Page', [
            'status' => $status,
            'message' => $this->messages[$status],
        ])->toResponse($request)
            ->setStatusCode($status);
    }
}
