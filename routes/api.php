<?php

use App\Http\Controllers\ApisController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


//================================================================================//
Route::get('/getAllAreas', [ApisController::class, 'getAllAreas']);
Route::post('/createArea', [ApisController::class, 'createArea']);
Route::put('/updateArea/{id}', [ApisController::class, 'updateArea']);
Route::delete('/deleteArea/{id}', [ApisController::class, 'deleteArea']);
//================================================================================//
Route::get('/getAllUserTypes', [ApisController::class, 'getAllUserTypes']);
//================================================================================//
Route::get('/getAllUsers', [ApisController::class, 'getAllUsers']);
Route::post('/createUser', [ApisController::class, 'createUser']);
Route::put('/updateUser/{id}', [ApisController::class, 'updateUser']);
Route::delete('/deleteUser/{id}', [ApisController::class, 'deleteUser']);
//===============================================================================
Route::get('/getAllAccounts', [ApisController::class, 'getAllAccounts']);
Route::put('/updateAccount/{id}', [ApisController::class, 'updateAccount']);
Route::get('/getAccountSummary', [ApisController::class, 'getAccountSummary']);
//===============================================================================
Route::get('/getAccountDetails/{accountId}', [ApisController::class, 'getAccountDetails']);
Route::post('/createAccountDetail/{accountId}', [ApisController::class, 'createAccountDetail']);
Route::put('/updateAccountDetail/{accountDetailId}', [ApisController::class, 'updateAccountDetail']);
Route::delete('/deleteAccountDetail/{accountDetailId}', [ApisController::class, 'deleteAccountDetail']);
Route::get('/getAccountSummary/{accountId}', [ApisController::class, 'getAccountSummary']);
//===============================================================================
Route::get('/getAllPaids', [ApisController::class, 'getAllPaids']);
Route::post('/createPaid', [ApisController::class, 'createPaid']);
Route::put('/updatePaid/{paidId}', [ApisController::class, 'updatePaid']);
Route::delete('/deletePaid/{paidId}', [ApisController::class, 'deletePaid']);
//===============================================================================
Route::get('/getAllArresteds', [ApisController::class, 'getAllArresteds']);
Route::post('/createArrested', [ApisController::class, 'createArrested']);
Route::put('/updateArrested/{ArrestedId}', [ApisController::class, 'updateArrested']);
Route::delete('/deleteArrested/{ArrestedId}', [ApisController::class, 'deleteArrested']);
//===============================================================================
Route::get('/getAllCategories', [ApisController::class, 'getAllCategories']);
Route::post('/createCategory', [ApisController::class, 'createCategory']);
Route::put('/updateCategory/{CategoryId}', [ApisController::class, 'updateCategory']);
Route::delete('/deleteCategory/{CategoryId}', [ApisController::class, 'deleteCategory']);
//=============================================================================

Route::get('/getAllInvoices', [ApisController::class, 'getAllInvoices']);
Route::post('/createInvoice', [ApisController::class, 'createInvoice']);
Route::put('/updateInvoice/{InvoiceId}', [ApisController::class, 'updateInvoice']);
Route::delete('/deleteInvoice/{InvoiceId}', [ApisController::class, 'deleteInvoice']);

//=============================================================================
