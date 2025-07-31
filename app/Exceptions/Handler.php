<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use App\Models\ErrorLog;
use Illuminate\Support\Facades\Auth;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        //
    }

    public function render($request, Throwable $exception)
    {
 

        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => 'خطأ في التحقق من البيانات.',
                'errors' => $exception->errors(),
            ], 422);
        }

        return parent::render($request, $exception);
    }

 public function report(Throwable $exception): void
{
    // تسجيل الخطأ في قاعدة بيانات مثلاً
    try {
        ErrorLog::create([
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'type' => get_class($exception),
            'user' => optional(Auth::user())->user_name ?? 'guest',
            'date' => now(),
        ]);
    } catch (\Throwable $e) {
        // لا تفشل العملية إذا فشل تسجيل الخطأ نفسه
    }

    parent::report($exception);
}

}
