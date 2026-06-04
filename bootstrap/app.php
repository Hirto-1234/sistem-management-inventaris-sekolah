<?php

use App\Http\Middleware\AuthenticatedPengguna;
use App\Http\Middleware\AuthPenggunaMiddleware;
use App\Http\Middleware\NoBackHistory;
use App\Http\Middleware\OnlyUnverified;
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
        //
        $middleware->alias([
            'authpenggunamiddleware' => AuthPenggunaMiddleware::class,
            'authenticatedpengguna' => AuthenticatedPengguna::class,
            'onlyunverified' => OnlyUnverified::class,
            'noback' => NoBackHistory::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
