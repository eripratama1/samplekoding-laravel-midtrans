<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        /**
         * Melakukan pengecualian penggunaaan csrf_token
         * pada route notification
         */
        $middleware->validateCsrfTokens(except:[
            '/notification'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
