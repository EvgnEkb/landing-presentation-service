<?php

use App\Http\Middleware\LogRequestMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Request;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->group('api', [
            HandleCors::class,
            LogRequestMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $exception, Request $request) {
            if ($request->expectsJson()) {
                $status = method_exists($exception, 'getStatusCode')
                    ? $exception->getStatusCode()
                    : 500;

                if ($status === 422) {
                    return null;
                }

                return response()->json([
                    'message' => $exception->getMessage() ?: 'Ошибка сервера',
                ], $status);
            }

            return null;
        });
    })->create();
