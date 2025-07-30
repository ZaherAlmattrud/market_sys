<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Version_1_2\ProductController;
use App\Http\Controllers\AuthController;

//==========================================================================================================================

Route::prefix('auth')->group(function () {

    Route::post('register', [AuthController::class, 'register']);      // تسجيل مستخدم جديد
 
        Route::post('login', [AuthController::class, 'login']);            // تسجيل الدخول


  
});


Route::middleware('auth:sanctum')->prefix('auth')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);          // تسجيل الخروج من الجلسة الحالية
    Route::post('logout-all', [AuthController::class, 'logoutAll']);   // تسجيل الخروج من كل الأجهزة
    Route::get('me', [AuthController::class, 'me']);                   // بيانات المستخدم الحالي
    Route::post('update-password', [AuthController::class, 'updatePassword']); // تحديث كلمة المرور




});
//===========================================================================================================================

Route::middleware('auth:sanctum')->group(function () {


    // ✅ القراءة والإضافة فقط (index, show, store)
    //==================================================================================================================
    Route::apiResource('products', ProductController::class)
        ->only(['index', 'show', 'store']);

    //==================================================================================================================
    // 🔐 التعديل والحذف فقط مع صلاحيات
    Route::middleware(['role:Admin|SuperAdmin'])->group(function () {
        Route::apiResource('products', ProductController::class)
            ->only(['update', 'destroy']);
    });

});
