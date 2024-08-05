<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Version_1_1\AccountController;
use App\Http\Controllers\Version_1_1\ArrestedsController;
use App\Http\Controllers\Version_1_1\UsersController;
use App\Http\Controllers\Version_1_1\PaidsController;
use App\Http\Controllers\Version_1_1\InvoicesController;
use App\Http\Controllers\Version_1_1\ProductsController;
use App\Models\Area;

class ApisController extends Controller
{
    //


    private $accountController;
    private $userController;
    private $arrestedsController;
    private $paidsController;
    private $invoicesController;
    private $productsController;

    function __construct(AccountController $accountController, UsersController $userController, ArrestedsController $arrestedsController, PaidsController $paidsController , InvoicesController $invoicesController ,
    ProductsController $productsController )
    {

        $this->accountController = $accountController;
        $this->userController = $userController;
        $this->arrestedsController = $arrestedsController;
        $this->paidsController = $paidsController;
        $this->invoicesController = $invoicesController;
        $this->productsController = $productsController;
    }

    public function  getAllAreas()
    {

        $data = DB::table('areas')->orderBy('id', 'desc')->get();

        $itemsArray = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        });

        return response()->json($itemsArray);
    }



    public function createArea(Request $request)
    {

        $data = $request->all();

        $res = DB::table('areas')->insert([
            'name' => $data['name']
        ]);

        return response()->json($$res);
    }

    public function deleteArea($id)
    {

        $res = DB::table('areas')->where('id', $id)->delete();
        return response()->json($res);
    }

    public function updateArea(Request $request, $id)
    {

        $data = $request->all();
        Log::info('Update');
        Log::info('Data');
        Log::info($data);

        $res =  DB::table('areas')
            ->where('id', $id)
            ->update(['name' => $data['name']]);

        return response()->json($res);
    }




    public function  getAllUserTypes()
    {

        $data = DB::table('usertypes')->orderBy('id', 'desc')->get();

        $itemsArray = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->type_name,
            ];
        });

        return response()->json($itemsArray);
    }


    public function getAllUsers()
    {




        $data = DB::table('users')->orderBy('id', 'desc')->get();

        $itemsArray = $data->map(function ($item) {



            $user = $item;

             $book =  DB::table('account_details')->where('account_id', $user->account_id)->sum('total'); //  الاجمالي
           $invoices =  DB::table('invoices')->where('account_id', $user->account_id)->sum('total'); //  الاجمالي
            $arresteds =  DB::table('arresteds')->where('account_id', $user->account_id)->sum('total'); // مقبوضات 
         $paids =  DB::table('paids')->where('account_id', $user->account_id)->sum('total'); // مدفوعات 
          $userTypeRow = DB::table('usertypes')->where('id', $user->user_type)->first();
            $debts = 0;

            $total =  $book  +   $invoices;


            if ($userTypeRow->type_name != 'مورد') {

                $debts = $total -    $arresteds; // الباقي = رصيده المديون - المقبوضات
                $debts =  $debts +   $paids; // الباقي النهائي = المدفوع + الباقي

            } else {

                $debts = $total -   $paids;
            }


            $userType =  DB::table('usertypes')
                ->where('id', $item->user_type)
                ->get('type_name')->first();




            $area =  DB::table('areas')
                ->where('id', $item->area_id)
                ->get(['name'])->first();

            return [
                'id' => $item->id,
                'user_name' => $item->user_name,
                'user_type' =>   $userType ?  $userType->type_name : 'غير محدد',
                'area' =>  $area  ? $area->name : 'غير محدد',
                'account' => $item->id,
                'number_in_book' => $item->number_in_book,
                'mobile' => $item->mobile,
               'debts' =>  $debts,
            ];
        });

        Log::info(' data : ' . $itemsArray);

        return response()->json($itemsArray);
    }

    public function createUser(Request $request)
    {

        $data = $request->all();

        Log::info($data);

        $res = DB::table('users')->insert(
            [
                'user_name' => $data['user_name'],
                'user_type' => $data['user_type'] ? $data['user_type'] : null,
                'area_id' => $data['area'] ? $data['area']  : null,
                'number_in_book' => $data['number_in_book'],
                'mobile' => $data['mobile'],
            ]
        );


        $user =  DB::table('users')->orderBy('id', 'desc')->first();

        // add new account to user
        DB::table('accounts')->insert(
            [
                'user_id' =>  $user->id,

            ]
        );

        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'account_id' =>    DB::table('accounts')->where('user_id', $user->id)->first()->id,
            ]);



        return response()->json($res);
    }

    public function updateUser(Request $request, $id)
    {

        $data = $request->all();


        $userTypeId = null;
        $areaId = null;




        if (gettype(($data['area'])) == 'integer') {

            $areaId = $data['area'];
        } else {

            $area =  DB::table('areas')
                ->where('name', $data['area'])
                ->get(['id'])->first();

            $areaId =  $area->id;
        }

        if (gettype(($data['user_type'])) == 'integer') {



            $userTypeId = $data['user_type'];
        } else {

            $userType =  DB::table('usertypes')
                ->where('type_name', $data['user_type'])
                ->get(['id'])->first();


            $userTypeId =  $userType->id;
        };


        $res =  DB::table('users')
            ->where('id', $id)
            ->update([
              //  'user_name' => $data['user_name'],
                'user_type' =>     $userTypeId,
                'area_id' =>   $areaId,
                'number_in_book' => $data['number_in_book'],
                'mobile' => $data['mobile'],
            ]);



        return response()->json($res);
    }


    public function deleteUser($id)
    {

        $res = DB::table('users')->where('id', $id)->delete();
        return response()->json($res);
    }

    public function  getAllAccounts()
    {


        return  $this->accountController->getAll();

        // $data = DB::table('accounts')->orderBy('id', 'desc')->get();

        // $itemsArray = $data->map(function ($item) {

        //     $user =  DB::table('users')->where('id', $item->user_id)->first();

        //     $book =  DB::table('account_details')->where('account_id', $item->id)->sum('total'); //  الاجمالي
        //     $invoices =  DB::table('invoices')->where('account_id', $item->id)->sum('total'); //  الاجمالي
        //     $arresteds =  DB::table('arresteds')->where('account_id', $item->id)->sum('total'); // مقبوضات 
        //     $paids =  DB::table('paids')->where('account_id', $item->id)->sum('total'); // مدفوعات 
        //     $userTypeRow = DB::table('usertypes')->where('id', $user->user_type)->first();
        //     $debts = 0;

        //     $total =  $book  +   $invoices;


        //     if ($userTypeRow->type_name != 'مورد') {

        //         $debts = $total -    $arresteds; // الباقي = رصيده المديون - المقبوضات
        //         $debts =  $debts +   $paids; // الباقي النهائي = المدفوع + الباقي

        //     } else {

        //         $debts = $total -   $paids;
        //     }

        //     return [
        //         'id' => $item->id,
        //         'account' => $item->account_num,
        //         'person_name' => $user->user_name,
        //         'account_user_type' => $userTypeRow  ? $userTypeRow->type_name  : null,
        //         'total' =>  $total,
        //         'invoices' =>   $invoices,
        //         'book' =>   $book,
        //         'paid' =>  $paids,
        //         'arrested' =>  $arresteds,
        //         'debts' =>  $debts

        //     ];
        // });

        // return response()->json($itemsArray);
    }



    public function updateAccount(Request $request, $id)
    {

        $data = $request->all();


        $res =  DB::table('accounts')
            ->where('id', $id)
            ->update(['account_num' => $data['account']]);

        return response()->json($res);
    }

    public function getAccountDetails($accountId)
    {

        Log::info("id : ");
        Log::info($accountId);


        $data = DB::table('account_details')->where('account_id', $accountId)->get();

        $book =  DB::table('account_details')->where('account_id', $accountId)->sum('total'); //  الاجمالي
        $invoices =  DB::table('invoices')->where('account_id', $accountId)->sum('total'); //  الاجمالي
        $arresteds =  DB::table('arresteds')->where('account_id', $accountId)->sum('total'); // مقبوضات 
        $paids =  DB::table('paids')->where('account_id', $accountId)->sum('total'); // مدفوعات 

        $user = DB::table('users')->where('account_id', $accountId)->first();
        $userTypeRow = DB::table('usertypes')->where('id', $user->user_type)->first();
        $debts = 0;

        $total =  $book  +   $invoices;


        if ($userTypeRow->type_name != 'مورد') {

            $debts = $total -    $arresteds; // الباقي = رصيده المديون - المقبوضات
            $debts =  $debts +   $paids; // الباقي النهائي = المدفوع + الباقي

        } else {

            $debts = $total -   $paids;
        }

        $res['total'] = $total;
        $res['invoices'] = $invoices;
        $res['book'] = $book;
        $res['arresteds'] = $arresteds;
        $res['paids'] = $paids;
        $res['debts'] = $debts;
        $res['account_persion'] = $user->user_name;
        $res['book_number'] = $user->number_in_book;
        $res['data'] = $data->map(function ($item) {

            return  $item;
        });

        return response()->json($res);
    }



    public function createAccountDetail(Request $request, $accountId)
    {

        $data = $request->all();

        $res = DB::table('account_details')->insert([

            'description' => $data['description'],
            'quantity' => $data['quantity'],
            'total' => $data['total'],
            'price' => $data['price'],
            'date' => Carbon::now()->format('Y-m-d H:i:s'),
            'account_id' =>  $accountId,
        ]);

        return response()->json($res);
    }

    public function updateAccountDetail(Request $request, $id)
    {

        $data = $request->all();


        $res =  DB::table('account_details')
            ->where('id', $id)
            ->update(

                [
                    'description' => $data['description'],
                    'quantity' => $data['quantity'],
                    'total' => $data['total'],
                    'price' => $data['price'],

                ]

            );

        return response()->json($res);
    }

    public function deleteAccountDetail($id)
    {

        $res = DB::table('account_details')->where('id', $id)->delete();
        return response()->json($res);
    }

    public function getAccountSummary($accountId)
    {

        $data = DB::table('account_details')->where('account_id', $accountId)->get();

        $res = $data->map(function ($item) {

            return  $item;
        });

        return response()->json($res);
    }


    public function getAllPaids()
    {

        return $this->paidsController->getAll();
        // $data = DB::table('paids')->orderBy('id', 'desc')->get();

        // $itemsArray = $data->map(function ($item) {

        //     $user =     DB::table('users')->where('account_id', $item->account_id)->first();


        //     return [
        //         'id' => $item->id,
        //         'total' => $item->total,
        //         'date' =>  $item->date,
        //         'notes' =>  $item->notes,
        //         'account_id' =>  $user->user_name,
        //     ];
        // });

        // return response()->json($itemsArray);
    }

    public function createPaid(Request $request)
    {

        $data = $request->all();

        $user = DB::table('users')->where('id', $data['account_id'])->first();

        $res = DB::table('paids')->insert([

            'total' =>  $data['total'],
            'date' =>    Carbon::now()->format('Y-m-d H:i:s'),
            'notes' =>   $data['notes'],
            'account_id' => $user->account_id,
        ]);

        return response()->json($res);
    }

    public function updatePaid(Request $request, $id)
    {



        return $this->paidsController->update($request, $id);


        // $data = $request->all();

        // $user = DB::table('users')->where('id', $data['account_id'])->first();

        // $res =  DB::table('paids')
        //     ->where('id', $id)
        //     ->update(

        //         [
        //             'total' =>  $data['total'],
        //             //  'date' =>   Carbon::now()->format('Y-m-d H:i:s'),
        //             'notes' =>   $data['notes'],
        //             'account_id' =>  $user->account_id,

        //         ]

        //     );

        // return response()->json($res);
    }

    public function deletePaid($id)
    {
        $res = DB::table('paids')->where('id', $id)->delete();
        return response()->json($res);
    }


    public function getAllArresteds()
    {

        return $this->arrestedsController->getAll();


        // $data = DB::table('arresteds')->orderBy('id', 'desc')->get();

        // $itemsArray = $data->map(function ($item) {

        //     $user =     DB::table('users')->where('account_id', $item->account_id)->first();


        //     return [
        //         'id' => $item->id,
        //         'total' => $item->total,
        //         'date' =>  $item->date,
        //         'notes' =>  $item->notes,
        //         'account_id' =>  $user->user_name,
        //     ];
        // });

        // return response()->json($itemsArray);
    }

    public function createArrested(Request $request)
    {

        $data = $request->all();

        $user = DB::table('users')->where('id', $data['account_id'])->first();

        $res = DB::table('arresteds')->insert([

            'total' =>  $data['total'],
            'date' =>    Carbon::now()->format('Y-m-d H:i:s'),
            'notes' =>   $data['notes'],
            'account_id' =>  $user->account_id,
        ]);

        return response()->json($res);
    }

    public function updateArrested(Request $request, $id)
    {



        $data = $request->all();

        $user = DB::table('users')->where('id', $data['account_id'])->first();
        $res =  DB::table('arresteds')
            ->where('id', $id)
            ->update(

                [
                    'total' =>  $data['total'],
                    //  'date' =>   Carbon::now()->format('Y-m-d H:i:s'),
                    'notes' =>   $data['notes'],
                    'account_id' =>  $user->account_id,

                ]

            );

        return response()->json($res);
    }

    public function deleteArrested($id)
    {
        $res = DB::table('arresteds')->where('id', $id)->delete();
        return response()->json($res);
    }

    public function getAllCategories()
    {

        $data = DB::table('categories')->orderBy('id', 'desc')->get();

        $itemsArray = $data->map(function ($item) {


            return [
                'id' => $item->id,
                'name' => $item->name,
                'descount' =>  $item->descount,

            ];
        });

        return response()->json($itemsArray);
    }

    public function createCategory(Request $request)
    {

        $data = $request->all();
        $res = DB::table('categories')->insert([
            'name' =>  $data['name'],
            'descount' =>  $data['descount'],
        ]);

        return response()->json($res);
    }

    public function updateCategory(Request $request, $id)
    {



        $data = $request->all();

        $res =  DB::table('categories')
            ->where('id', $id)
            ->update(

                [
                    'name' =>  $data['name'],
                    'descount' =>  $data['descount'],

                ]

            );

        return response()->json($res);
    }

    public function deleteCategory($id)
    {
        $res = DB::table('categories')->where('id', $id)->delete();
        return response()->json($res);
    }

    public function getAllInvoices()
    {

        $data = DB::table('invoices')->orderBy('id', 'desc')->get();


        $itemsArray = $data->map(function ($item) {

            $user =     DB::table('users')->where('account_id', $item->account_id)->first();

            return [

                'id' => $item->id,
                'file_url' =>  $item->file_url ?   $item->file_url  : null,
                'num' => $item->num ? $item->num : 'لا يوجد',
                'invoice_type' => $item->invoice_type,
                'account_id' =>  $user ? $user->user_name : null,
                'total' => $item->total,
                'date' => $item->date,
                'file' => $item->file,


            ];
        });

        return response()->json($itemsArray);
    }

    public function createInvoice(Request $request)
    {

        return $this->invoicesController->create($request);

        // $data = $request->all();

        // $user = DB::table('users')->where('id', $data['account_id'])->first();

        // $userTypeId = $user ? $user->user_type : null;

        // $userType = DB::table('usertypes')->where('id',  $userTypeId)->first();

        // $InvoiceType =   $userType->type_name == 'مورد' ? 'شراء' : 'بيع';

        // $fileName = '';
        // $filePath = '';
        // $fileUrl = '';

        // if ($request->hasFile('file')) {

        //     $file = $request->file('file');
        //     $fileName  =  time() . '1' . $file->getClientOriginalName();
        //     $filePath =    $file->storeAs('public', $fileName);
        //     $fileUrl = Storage::url($filePath);
        // }

        // $res = DB::table('invoices')->insert([

        //     'invoice_type' =>  $InvoiceType,
        //     'account_id' =>  $user ? $user->account_id : null,
        //     'total' => $data['total'] ? $data['total'] : '0',
        //     'num' => $data['num'],
        //     'date' =>  Carbon::now()->format('Y-m-d H:i:s'),
        //     'file' => '',
        //     'file_name' =>  $fileName,
        //     'file_path' =>   $filePath,
        //     'file_url' =>   $fileUrl,
        // ]);



        // return response()->json($res);
    }

    public function updateInvoice(Request $request, $id)
    {


        Log::info($request->all());
           
     return $this->invoicesController->update( $request, $id);


        // $oldInvoice = DB::table('invoices')->where('id', $id)->first();

        // Log::info($request->all());

        // $data = $request->all();

        // $res =  DB::table('invoices')
        //     ->where('id', $id)
        //     ->update(

        //         [
        //             'invoice_type' =>  $oldInvoice->invoice_type,
        //             'total' => $data['total'],
        //             'date' =>  Carbon::now()->format('Y-m-d H:i:s'),
        //             'num' => $data['num'],
        //             'file' => '',

        //         ]

        //     );

        // return response()->json($res);
    }

    public function deleteInvoice($id)
    {
        $res = DB::table('invoices')->where('id', $id)->delete();
        return response()->json($res);
    }

    public function getAllProducts()
    {

        return $this->productsController->getAll();

        // $data = DB::table('products')->orderBy('id', 'desc')->get();

        // $itemsArray = $data->map(function ($item) {

        //     $invoice =     DB::table('invoices')->where('id', $item->invoice_id)->first();
        //     $category =     DB::table('categories')->where('id', $item->category_id)->first();
        //     $exchange =     DB::table('exchange')->first();



        //     return [
        //         'id' => $item->id,
        //         'name' => $item->name,
        //         'code' => $item->code,
        //         'date' => $invoice ? $invoice->date :  $item->date ,
        //         'price'     => $item->price,
        //         'price_after_descount'     =>  $category ? ($item->price) - ($category->descount * $item->price) : $item->price,
        //         'notes' => $item->notes,
        //         'sell' => $item->sell,
        //         'price_in_dollar' => $item->price_in_dollar,
        //         //'img' => $item->img,
        //         'invoice_id' => $item->invoice_id,
        //         'category_id' => $category ?  $category->name : null,
        //         'updatingPrice' => $item->price_in_dollar *   $exchange->value,


        //     ];
        // });

        // return response()->json($itemsArray);
    }

    public function getProductImgLink($id)
    {


        if( $id > 560 ){

            return $this->productsController->getProductImg($id);


        }else{

                $data = [];
        $invoice =     DB::table('products')->where('id', intval($id))->first();
        $data['link']  =  $invoice ?  $invoice->file_url : null;

        return response()->json($data);


        }
        
           

    
    }

    public function getAllProductsHealthy()
    {

        $data = DB::table('products')->orderBy('id', 'desc')->get();

        $itemsArray = $data->map(function ($item) {





            $category =     DB::table('categories')->where('id', $item->category_id)->first();
            $invoice =     DB::table('invoices')->where('id', $item->invoice_id)->first();

            Log::info(Str::contains($category->name,  'سعد'));

            if (Str::contains($category->name,  'سعد') == 1) {

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'date' => $invoice ? $invoice->date : $item->date ,
                    'price'     => $item->price,
                    'price_after_descount'     => $item->price_after_descount ?   $item->price_after_descount : ($item->price) - ($category->descount * $item->price),
                    'notes' => $item->notes,
                    'sell' => $item->sell,
                    'img' => $item->img,
                    // 'invoice_id' => $item->invoice_id,
                    // 'category_id' => $category ?  $category->name : null,


                ];
            }
        });

        return response()->json($itemsArray);
    }

    public function createProduct(Request $request)
    {

        return $this->productsController->create($request);

        // $data = $request->all();


        // $fileName = '';
        // $filePath = '';
        // $fileUrl = '';

        // if ($request->hasFile('file')) {

        //     $file = $request->file('file');
        //     $fileName  =  time() . '1' . $file->getClientOriginalName();
        //     $filePath =    $file->storeAs('public', $fileName);
        //     $fileUrl = Storage::url($filePath);
        // }

        // $exchange =     DB::table('exchange')->first();
        // $category =     DB::table('categories')->where('id', $data['category_id'])->first();

        // $res = DB::table('products')->insert([

        //     'name' =>  $data['name'],
        //     'code' =>  $data['code'] ? $data['code'] : 0,
        //     'price'     => $data['price'],
        //     'notes' =>   $data['notes'],
        //     'price_in_dollar' => $data['pricr_in_doller'],
        //     'sell' =>  $data['sell'],
        //     'img' =>  $data['img'],
        //     'invoice_id' =>  $data['invoice_id'],
        //     'category_id' =>  $data['category_id'],
        //     'date' => Carbon::now()->format('Y-m-d H:i:s'),
        //     'file_name' =>    $fileName,
        //     'file_path' =>    $filePath,
        //     'file_url' =>   $fileUrl,
        //     'price_after_descount' => $category ? ($data['price']) - ($category->descount *  $data['price']) :  $data['price'],
        //     'price_in_dollar' =>   $data['price'] /   $exchange->value,

        // ]);
        // return response()->json($res);
    }

    public function updateProduct(Request $request, $id)
    {



        $data = $request->all();

        $fileName = '';
        $filePath = '';
        $fileUrl = '';

        if ($request->hasFile('file')) {

            $file = $request->file('file');
            $fileName  =  time() . '1' . $file->getClientOriginalName();
            $filePath =    $file->storeAs('public', $fileName);
            $fileUrl = Storage::url($filePath);
        }


        $res =  DB::table('products')
            ->where('id', $id)
            ->update(

                [

                    'name' =>  $data['name'],
                    'price' =>  $data['price'],
                    'category_id' =>  $data['category_id'],
                    'sell' =>   $data['sell'],
                    'img' =>    null,
                    'file_name' =>    $fileName,
                    'file_path' =>    $filePath,
                    'file_url' =>   $fileUrl,
                ]

            );

        return response()->json($res);
    }

    public function deleteProduct($id)
    {
        $res = DB::table('products')->where('id', $id)->delete();
        return response()->json($res);
    }

    public function getInvoiceImgLink($id)
    {


        if($id > 82 ){

         return $this->invoicesController->getInvoiceImg(intval($id));

        }else{

        $data = [];
        $invoice =     DB::table('invoices')->where('id',  $id )->first();
        $data['link']  =  $invoice ?  $invoice->file_url : null;
        Log::info("getInvoiceImgLink");
        Log::info($data);
        return response()->json($data);


        }
      
 
    }



    public function getCustomersReport(){

    }

    public function getSupplersReport(){

        
        $data = [];
        $userTypeExepted = ['مورد'];
        $debtstotalCustomers = 0;

        $regions = Area::with(['users.account.accountDetails', 'users.account.invoices', 'users.userType'])
            ->get()
            ->map(function ($region) use ($userTypeExepted) {


                $total = $region->users
                    ->filter(function ($user) use ($userTypeExepted) {

                        if (in_array($user->userType->type_name, $userTypeExepted))
                            return true;
                        else
                            return false;
                    })
                    ->sum(function ($user) {

                        $account = $user->account;

                        $accountDetailsSum = $account->accountDetails->sum('total');
                        $invoicesSum = $account->invoices->sum('total');
                        return $accountDetailsSum + $invoicesSum;
                    });

                $arresteds = $region->users
                    ->filter(function ($user) use ($userTypeExepted) {

                        if (in_array($user->userType->type_name, $userTypeExepted))
                            return true;
                        else
                            return false;
                    })
                    ->sum(function ($user) {

                        $account = $user->account;

                        return   $account->arresteds->sum('total');
                    });



                $paids = $region->users
                    ->filter(function ($user) use ($userTypeExepted) {

                        if (in_array($user->userType->type_name, $userTypeExepted))
                            return true;
                        else
                            return false;
 
                    })
                    ->sum(function ($user) {

                        $account = $user->account;
                        return   $account->paids->sum('total');
                    });

                $debts = $total +   $arresteds; // الباقي = رصيده المديون + المقبوضات
                $debts =  $debts -   $paids; // الباقي النهائي = المدفوع - الباقي



                return [
                    'id' =>  $region->id,
                    'name' => $region->name,
                    'total' => $total,
                    'arresteds' => $arresteds,
                    'paids' => $paids,
                    'debts' =>  $debts,
                ];
            });

        foreach ($regions as $region) {

            // $d['id'] = $region['id'];
            // $d['name'] = $region['name'];
            // $d['total'] = $region['total'];
            // $d['arresteds'] = $region['arresteds'];
            // $d['paids'] = $region['paids'];
            $d['debts'] = $region['debts'];

            $debtstotalCustomers =  $debtstotalCustomers +  $d['debts'];



            array_push($data, $d);
        }

        return $debtstotalCustomers ;


    }

    public function getReport()
    {

        $data = [];

        $userTypeExepted = ['زبون'];

        // $total = 0;
        // $arresteds = 0;
        // $paids = 0;

        $debtstotalSupplers = $this->getSupplersReport();
        $debtstotalCustomers = 0;

        $regions = Area::with(['users.account.accountDetails', 'users.account.invoices', 'users.userType'])
            ->get()
            ->map(function ($region) use ($userTypeExepted) {


                $total = $region->users
                    ->filter(function ($user) use ($userTypeExepted) {

                        if (in_array($user->userType->type_name, $userTypeExepted))
                            return true;
                        else
                            return false;
                    })
                    ->sum(function ($user) {

                        $account = $user->account;

                        $accountDetailsSum = $account->accountDetails->sum('total');
                        $invoicesSum = $account->invoices->sum('total');
                        return $accountDetailsSum + $invoicesSum;
                    });

                $arresteds = $region->users
                    ->filter(function ($user) use ($userTypeExepted) {

                        if (in_array($user->userType->type_name, $userTypeExepted))
                            return true;
                        else
                            return false;

                        // if ($user->userType->type_name != 'مورد' /*|| $user->userType->type_name != 'مدير'  */)
                        //     return true;
                        // else
                        //     return false;
                    })
                    ->sum(function ($user) {

                        $account = $user->account;

                        return   $account->arresteds->sum('total');
                    });



                $paids = $region->users
                    ->filter(function ($user) use ($userTypeExepted) {

                        if (in_array($user->userType->type_name, $userTypeExepted))
                            return true;
                        else
                            return false;

                        // if ($user->userType->type_name != 'مورد' /*|| $user->userType->type_name != 'مدير'  */)
                        //     return true;
                        // else
                        //     return false;
                    })
                    ->sum(function ($user) {

                        $account = $user->account;
                        return   $account->paids->sum('total');
                    });

                $debts = $total -    $arresteds; // الباقي = رصيده المديون - المقبوضات
                $debts =  $debts +   $paids; // الباقي النهائي = المدفوع + الباقي



                return [
                    'id' =>  $region->id,
                    'name' => $region->name,
                    'total' => $total,
                    'arresteds' => $arresteds,
                    'paids' => $paids,
                    'debts' =>  $debts,
                ];
            });

        foreach ($regions as $region) {

            $d['id'] = $region['id'];
            $d['name'] = $region['name'];
            $d['total'] = $region['total'];
            $d['arresteds'] = $region['arresteds'];
            $d['paids'] = $region['paids'];
            $d['debts'] = $region['debts'];

            $debtstotalCustomers =  $debtstotalCustomers +  $d['debts'];



            array_push($data, $d);
        }

        $response = [];
        $response['data'] = $data;
        $response['debtstotalSupplers'] = $debtstotalSupplers;
        $response['debtstotalCustomers'] = $debtstotalCustomers;

        return response()->json($response);
    }
}
