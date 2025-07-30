<?php

use App\Http\Controllers\ActivityLogController;
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
use App\Http\Controllers\Version_1_1\CategoriesController;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Version_1_1\UserTypesController;
use App\Http\Controllers\Version_1_1\AreasController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\RolePermissionController;




use App\Http\Controllers\AuthController;


Route::middleware(['auth:sanctum', 'role:SuperAdmin'])->prefix('authorization')->group(function () {



    Route::get('/roles', [RolePermissionController::class, 'roles']);
    Route::get('/roles/{id}', [RolePermissionController::class, 'getRole']);
    Route::get('/roles/permissions/{id}', [RolePermissionController::class, 'getPermissionsForRole']);
    Route::post('/roles/create', [RolePermissionController::class, 'createRole']);
    Route::put('/roles/update', [RolePermissionController::class, 'updateRole']);
    Route::delete('/roles/delete/{id}', [RolePermissionController::class, 'deleteRole']);
    Route::post('/roles/updatePermissions/{roleId}', [RolePermissionController::class, 'updateRolePermissions']);


    Route::get('/permissions', [RolePermissionController::class, 'permissions']);
    Route::post('/permissions/create', [RolePermissionController::class, 'createPermission']);
    Route::delete('/permissions/delete/{id}', [RolePermissionController::class, 'deletePermission']);

    Route::post('/assign-role', [RolePermissionController::class, 'assignRole']); //->middleware('role:admin');
    Route::post('/remove-role', [RolePermissionController::class, 'removeRole']); //->middleware('role:admin');

    Route::post('/give-permission', [RolePermissionController::class, 'givePermission']); //->middleware('role:admin');
    Route::post('/remove-permission', [RolePermissionController::class, 'removePermission']); //->middleware('role:admin');
});

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


Route::middleware('auth:sanctum')->group(function () {




    //================================================================================//

    Route::get('/getAllAreas', [AreasController::class, 'getAll']);
    Route::post('/createArea', [AreasController::class, 'create']);

    Route::middleware('role:SuperAdmin')->group(function () {

        Route::put('/updateArea/{id}', [AreasController::class, 'update']);
        Route::delete('/deleteArea/{id}', [AreasController::class, 'delete']);
    });

    //================================================================================//
    Route::get('/getAllUserTypes', [ApisController::class, 'getAllUserTypes']);

    // مجموعة خاصة بأنواع المستخدمين
    Route::prefix('user-types')->group(function () {
        // جلب كل الأنواع
        Route::get('/', [UserTypesController::class, 'getAll']);

        // جلب نوع مستخدم محدد بالـ ID
        Route::get('/{id}', [UserTypesController::class, 'get']);

        // إنشاء نوع جديد
        Route::post('/', [UserTypesController::class, 'create']);

        Route::middleware('role:SuperAdmin')->group(function () {

            // تعديل نوع موجود
            Route::put('/{id}', [UserTypesController::class, 'update']);

            // حذف نوع مستخدم
            Route::delete('/{id}', [UserTypesController::class, 'delete']);
        });
    });
    //================================================================================//


    Route::get('/getAllUserWithPagination', [UsersController::class, 'getAllUserWithPagination']);
    Route::get('/getAllSystemUsers', [UsersController::class, 'getAllSystemUsers']);
    Route::get('/getAllUsers', [UsersController::class, 'getAll']);
    Route::post('/createUser', [UsersController::class, 'create']);
    Route::get('getUserInfo/{id}', [UsersController::class, 'getUserInfo']);


    Route::middleware('role:SuperAdmin')->group(function () {

        Route::put('/updateUser/{id}', [UsersController::class, 'update']);
        Route::delete('/deleteUser/{id}', [UsersController::class, 'delete']);
    });

    //===============================================================================

    Route::get('/getAllAccounts', [AccountController::class, 'getAll']);
    Route::get('/getAccountSummary', [AccountController::class, 'getAccountSummaryTotal']);
    Route::get('/getAllAccountsCash', [AccountController::class, 'getAllAccountsCash']);

    Route::middleware('role:SuperAdmin')->group(function () {

        Route::delete('/clearAccount/{id}', [AccountController::class, 'clearAccount']);
        Route::put('/updateAccount/{id}', [AccountController::class, 'update']);
    });




    //===============================================================================
    Route::get('/getAccountDetails/{accountId}', [AccountDetailsController::class, 'getAccountDetails']);
    Route::post('/createAccountDetail/{accountId}', [AccountDetailsController::class, 'create']);
    Route::get('/getAccountSummary/{accountId}', [ApisController::class, 'getAccountSummary']);


    Route::middleware('role:SuperAdmin')->group(function () {

        Route::put('/updateAccountDetail/{accountDetailId}', [AccountDetailsController::class, 'update']);
        Route::delete('/deleteAccountDetail/{accountDetailId}', [ApisController::class, 'deleteAccountDetail']);
    });



    //===============================================================================
    Route::get('/getAllPaids', [ApisController::class, 'getAllPaids']);
    Route::post('/createPaid', [ApisController::class, 'createPaid']);

    Route::middleware('role:SuperAdmin')->group(function () {

        Route::put('/updatePaid/{paidId}', [PaidsController::class, 'update']);
        Route::delete('/deletePaid/{paidId}', [ApisController::class, 'deletePaid']);
    });


    //===============================================================================
    Route::get('/getAllArresteds', [ApisController::class, 'getAllArresteds']);
    Route::post('/createArrested', [ApisController::class, 'createArrested']);

    Route::middleware('role:SuperAdmin')->group(function () {
        Route::put('/updateArrested/{ArrestedId}', [ArrestedsController::class, 'update']);
        Route::delete('/deleteArrested/{ArrestedId}', [ApisController::class, 'deleteArrested']);
    });


    //===============================================================================
    Route::get('/getAllCategoriesForList', [CategoriesController::class, 'getAllCategoriesForList']);
    Route::get('/getAllCategories', [ApisController::class, 'getAllCategories']);
    Route::post('/createCategory', [ApisController::class, 'createCategory']);


    Route::middleware('role:SuperAdmin')->group(function () {

        Route::put('/updateCategory/{CategoryId}', [ApisController::class, 'updateCategory']);
        Route::delete('/deleteCategory/{CategoryId}', [ApisController::class, 'deleteCategory']);
    });
    //=============================================================================
    Route::get('/getAllInvoices', [ApisController::class, 'getAllInvoices']);
    Route::get('/getInvoiceImgLink/{invoiceId}', [ApisController::class, 'getInvoiceImgLink']);
    Route::post('/createInvoice', [ApisController::class, 'createInvoice']);

    Route::middleware('role:SuperAdmin')->group(function () {


        Route::post('/updateInvoice/{InvoiceId}', [InvoicesController::class, 'update']);
        Route::delete('/deleteInvoice/{InvoiceId}', [InvoicesController::class, 'delete']);
    });

    Route::get('/getAllInvoicesForList', [InvoicesController::class, 'getAllInvoicesForList']);
    Route::get('/invoice/photo/{id}', [InvoicesController::class, 'getPhoto']);


    //=============================================================================

    Route::get('/getAllProductsForList', [ProductsController::class, 'getAllProductsForList']);
    Route::get('/products/{id}/price', [ProductsController::class, 'getCalculatedPrice']);
    Route::get('/getAllProductsHealthy', [ApisController::class, 'getAllProductsHealthy']);
    Route::post('/createProduct', [ProductsController::class, 'create']);
    Route::get('/products', [ProductsController::class, 'getAll']);
    Route::get('/products/{id}', [ProductsController::class, 'show']);
    Route::post('/products', [ProductsController::class, 'save']);

    Route::middleware('role:SuperAdmin')->group(function () {



        Route::put('/updateProduct/{ProductId}', [ProductsController::class, 'update']);
        Route::delete('/deleteProduct/{ProductId}', [ApisController::class, 'deleteProduct']);
        Route::patch('/products/{id}', [ProductsController::class, 'update']);
        Route::delete('/products/{id}', [ProductsController::class, 'delete']);
    });
    //=============================================================================
    Route::get('/report', [ApisController::class, 'getReport']);
    //=============================================================================
    Route::get('/getAllDays', [DayController::class, 'getAll']);
    Route::post('/createDay', [DayController::class, 'create']);


    Route::middleware('role:SuperAdmin')->group(function () {

        Route::put('/updateDay/{Id}', [DayController::class, 'update']);
        Route::delete('/deleteDay/{Id}', [DayController::class, 'delete']);
    });
    //=============================================================================

    Route::get('/getAll', [ExchangeController::class, 'getAll']);
    Route::post('/createExchange', [ExchangeController::class, 'create']);


    Route::middleware('role:SuperAdmin')->group(function () {

        Route::put('/updateExchange/{Id}', [ExchangeController::class, 'update']);
        Route::delete('/deleteExchange/{Id}', [ExchangeController::class, 'delete']);
    });

    //=============================================================================
    Route::get('/getAllSells', [SellController::class, 'index']);
    Route::post('/createSell', [SellController::class, 'store']);
    Route::put('/updateSell/{Id}', [SellController::class, 'update']);
    Route::delete('/deleteSell/{Id}', [SellController::class, 'destroy']);
    //=============================================================================

    //=============================================================================
    Route::get('/getAllSellDetails/{sellId}', [SellDetailController::class, 'index']);
    Route::post('/createSellDetail/{sellId}', [SellDetailController::class, 'store']);

    Route::middleware('role:SuperAdmin')->group(function () {


        Route::put('/updateSellDetail/{Id}', [SellDetailController::class, 'update']);
        Route::delete('/deleteSellDetail/{Id}', [SellDetailController::class, 'destroy']);
    });
    //=============================================================================

    Route::get('/exchange', [ExchangeController::class, 'getExchange']);

    //=============================================================================

    Route::middleware('role:SuperAdmin')->group(function () {


        Route::get('audits', [AuditController::class, 'index']);
        Route::get('/getLastFiveActivityLog', [AuditController::class, 'getLastFive']);
    });

    //=============================================================================

});
