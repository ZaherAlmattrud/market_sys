<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use App\Models\ErrorLog;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',


    )->withMiddleware(function (Middleware $middleware) {
        // تسجيل Middleware الخاصة بـ Spatie
         $middleware->alias([
        'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
    ]);
    })->withMiddleware(function (Middleware $middleware) {
        //

    })->withMiddleware(function (Middleware $middleware) {
        $middleware->append(HandleCors::class); // أضف هذا السطر
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //

      $exceptions->report(function (Throwable $exception) {
            ErrorLog::create([
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'type' => get_class($exception),
            'user' => optional(Auth::user())->user_name ?? 'guest',
            'date' => now(),
        ]);
        });
    })->create();
