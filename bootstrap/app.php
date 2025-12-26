<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'onlyRole' => \App\Http\Middleware\CheckUserRole::class,
            'hasPermission' => \App\Http\Middleware\CheckUserHasPermission::class,
            'isAdminOrGameSessionOwner' => \App\Http\Middleware\CheckAdminOrGameSessionOwner::class,
            'verified.reviewer' => \App\Http\Middleware\EnsureUserIsReviewed::class,
        ]);

        // Web middleware stack
        $middleware->web(append: [
            SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
