<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(function (Illuminate\Foundation\Application $app) {
        $app->middleware([
            // \App\Http\Middleware\TrustHosts::class,
            // \App\Http\Middleware\TrustProxies::class,
            // \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
            // \App\Http\Middleware\ValidatePostSize::class,
            // \App\Http\Middleware\TrimStrings::class,
            // \App\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);

        $app->aliasMiddleware([
            // 'auth' => \App\Http\Middleware\Authenticate::class,
        ]);
    })
    ->withExceptions(function (Illuminate\Foundation\Application $app) {
        //
    })
    ->create();
