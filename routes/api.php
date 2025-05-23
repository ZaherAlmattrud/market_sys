<?php

use App\Http\Controllers\ApisController;
use App\Http\Controllers\Version_1_1\DayController;
use App\Http\Controllers\Version_1_1\ExchangeController;
use App\Http\Controllers\Version_1_1\SellController;
use App\Http\Controllers\Version_1_1\SellDetailController;
use App\Http\Controllers\Version_1_1\AccountController;
use App\Http\Controllers\Version_1_1\AccountDetailsController;
use App\Http\Controllers\Version_1_1\ProductsController;
use App\Http\Controllers\Version_1_1\UsersController;
use App\Http\Controllers\Version_1_1\InvoicesController;
use App\Http\Controllers\Version_1_1\PaidsController;
use App\Http\Controllers\Version_1_1\ArrestedsController;
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
Route::get('/getAllUsers', [UsersController::class, 'getAll']);
Route::post('/createUser', [ApisController::class, 'createUser']);
Route::put('/updateUser/{id}', [UsersController::class, 'update']);
Route::delete('/deleteUser/{id}', [ApisController::class, 'deleteUser']);
//===============================================================================
Route::get('/getAllAccounts', [ApisController::class, 'getAllAccounts']);
Route::put('/updateAccount/{id}', [ApisController::class, 'updateAccount']);
Route::get('/getAccountSummary', [ApisController::class, 'getAccountSummary']);
Route::delete('/clearAccount/{id}', [AccountController::class, 'clearAccount']);
Route::get('/getAllAccountsCash', [AccountController::class, 'getAllAccountsCash']);



//===============================================================================
Route::get('/getAccountDetails/{accountId}', [ApisController::class, 'getAccountDetails']);
Route::post('/createAccountDetail/{accountId}', [ApisController::class, 'createAccountDetail']);
Route::put('/updateAccountDetail/{accountDetailId}', [AccountDetailsController::class, 'update']);
Route::delete('/deleteAccountDetail/{accountDetailId}', [ApisController::class, 'deleteAccountDetail']);
Route::get('/getAccountSummary/{accountId}', [ApisController::class, 'getAccountSummary']);
//===============================================================================
Route::get('/getAllPaids', [ApisController::class, 'getAllPaids']);
Route::post('/createPaid', [ApisController::class, 'createPaid']);
Route::put('/updatePaid/{paidId}', [PaidsController::class, 'update']);
Route::delete('/deletePaid/{paidId}', [ApisController::class, 'deletePaid']);
//===============================================================================
Route::get('/getAllArresteds', [ApisController::class, 'getAllArresteds']);
Route::post('/createArrested', [ApisController::class, 'createArrested']);
Route::put('/updateArrested/{ArrestedId}', [ArrestedsController::class, 'update']);
Route::delete('/deleteArrested/{ArrestedId}', [ApisController::class, 'deleteArrested']);
//===============================================================================
Route::get('/getAllCategories', [ApisController::class, 'getAllCategories']);
Route::post('/createCategory', [ApisController::class, 'createCategory']);
Route::put('/updateCategory/{CategoryId}', [ApisController::class, 'updateCategory']);
Route::delete('/deleteCategory/{CategoryId}', [ApisController::class, 'deleteCategory']);
//=============================================================================
Route::get('/getAllInvoices', [ApisController::class, 'getAllInvoices']);
Route::get('/getInvoiceImgLink/{invoiceId}', [ApisController::class, 'getInvoiceImgLink']);
Route::post('/createInvoice', [ApisController::class, 'createInvoice']);
Route::put('/updateInvoice/{InvoiceId}', [InvoicesController::class, 'update']);
Route::delete('/deleteInvoice/{InvoiceId}', [ApisController::class, 'deleteInvoice']);


//=============================================================================

Route::put('/updateProduct/{ProductId}', [ProductsController::class, 'update']);
Route::get('/getAllProducts', [ApisController::class, 'getAllProducts']);
Route::get('/getProductImgLink/{productId}', [ApisController::class, 'getProductImgLink']);
Route::get('/getAllProductsHealthy', [ApisController::class, 'getAllProductsHealthy']);
Route::post('/createProduct', [ProductsController::class, 'create']);

Route::delete('/deleteProduct/{ProductId}', [ApisController::class, 'deleteProduct']);
//=============================================================================
Route::get('/report', [ApisController::class, 'getReport']);
//=============================================================================
Route::get('/getAllDays', [DayController::class, 'getAll']);
Route::post('/createDay', [DayController::class, 'create']);
Route::put('/updateDay/{Id}', [DayController::class, 'update']);
Route::delete('/deleteDay/{Id}', [DayController::class, 'delete']);
//=============================================================================

Route::get('/getAll', [ExchangeController::class, 'getAll']);
Route::post('/createExchange', [ExchangeController::class, 'create']);
Route::put('/updateExchange/{Id}', [ExchangeController::class, 'update']);

//=============================================================================
Route::get('/getAllSells', [SellController::class, 'index']);
Route::post('/createSell', [SellController::class, 'store']);
Route::put('/updateSell/{Id}', [SellController::class, 'update']);
Route::delete('/deleteSell/{Id}', [SellController::class, 'destroy']);
//=============================================================================

//=============================================================================
Route::get('/getAllSellDetails/{sellId}', [SellDetailController::class, 'index']);
Route::post('/createSellDetail/{sellId}', [SellDetailController::class, 'store']);
Route::put('/updateSellDetail/{Id}', [SellDetailController::class, 'update']);
Route::delete('/deleteSellDetail/{Id}', [SellDetailController::class, 'destroy']);
//=============================================================================

