<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: 'api', // APIのプレフィックス
    )
    ->withMiddleware(function (Middleware $middleware) {
        // CORS設定
        $middleware->validateCsrfTokens(except: [
            'api/*', // API routesはCSRF除外
        ]);

        // APIミドルウェアグループの設定
        $middleware->group('api', [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // CORS許可設定（必要に応じて）
        $middleware->appendToGroup('api', [
            // 追加のCORS設定がある場合はここに
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
