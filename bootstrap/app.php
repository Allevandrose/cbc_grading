<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleManager;
use App\Http\Middleware\EnsureTeacherIsSetup;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'role'          => RoleManager::class,
            'teacher.setup' => EnsureTeacherIsSetup::class,
        ]);

        // Add middleware to the global web group if you want it to run on every request
        $middleware->web(append: [
            // If you want EVERY teacher request to check setup automatically, 
            // you can uncomment the line below. Otherwise, use it in routes/web.php
            // EnsureTeacherIsSetup::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
