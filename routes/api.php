<?php

use App\Http\Controllers\ApisController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::get('/getAllAreas', [ApisController::class, 'getAllAreas']);
Route::post('/createArea' ,[ApisController::class , 'createArea']);
Route::put('/updateArea/{id}', [ApisController::class, 'updateArea']);
Route::delete('/deleteArea/{id}' ,[ApisController::class , 'deleteArea']);
 




