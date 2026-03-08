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
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withMiddleware(function (\Illuminate\Foundation\Configuration\Middleware $middleware) {
    $middleware->alias([
        'auth.zoo'     => \App\Http\Middleware\AuthMiddleware::class,
        'auth.admin'   => \App\Http\Middleware\AdminMiddleware::class,
        'auth.guia'    => \App\Http\Middleware\GuiaMiddleware::class,
        'auth.cliente' => \App\Http\Middleware\ClienteMiddleware::class,
    ]);
})
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

    