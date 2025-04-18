<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Log\Logger;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Tambahkan middleware global di sini
        $middleware->append(
            \Illuminate\Session\Middleware\StartSession::class
            // Tambahkan middleware lain jika diperlukan
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Penanganan error khusus untuk route API
        $exceptions->renderable(function (\Throwable $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'Something went wrong!',
                    'message' => $e->getMessage(),
                ], 500);
            }
        });
    })
    ->withLogging(function (Logger $logger) {
        // Logging sederhana saat aplikasi di-boot
        $logger->info('Application booted successfully.');
    })
    ->create();
