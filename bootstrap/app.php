<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleManager;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'role' => RoleManager::class,
        ]);

        // Add middleware to web group
        $middleware->web(append: [
            // You can add custom web middleware here
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
