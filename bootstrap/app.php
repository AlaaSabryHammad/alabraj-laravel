<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'check.password.changed' => \App\Http\Middleware\CheckPasswordChanged::class,
            'manager.only' => \App\Http\Middleware\CheckManagerRole::class,
            'log.requests' => \App\Http\Middleware\LogRequests::class,
            'check.employee.status' => \App\Http\Middleware\CheckEmployeeStatus::class,
        ]);

        // إضافة الـ middleware لجميع الـ web routes
        $middleware->web(append: [
            \App\Http\Middleware\LogRequests::class,
            \App\Http\Middleware\CheckEmployeeStatus::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
