<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequestMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        Log::channel('daily')->info('API Request', [
            'method'   => $request->method(),
            'url'      => $request->fullUrl(),
            'ip'       => $request->ip(),
            'payload'  => $request->except(['password', 'password_confirmation']),
            'headers'  => $request->headers->all(),
        ]);

        $response = $next($request);

        Log::channel('daily')->info('API Response', [
            'status'   => $response->status(),
            'content'  => $response->getContent() ?: null,
        ]);

        return $response;
    }
}
