<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ApisController extends Controller
{
    //

    public function  getAllAreas()
    {

        $data = DB::table('areas')->get();

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

        $data = DB::table('usertypes')->get();

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

        $data = DB::table('users')->get();

        $itemsArray = $data->map(function ($item) {

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
            ];
        });

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
                'user_name' => $data['user_name'],
                'user_type' =>     $userTypeId,
                'area_id' =>   $areaId,
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

        $data = DB::table('accounts')->get();

        $itemsArray = $data->map(function ($item) {

            $user =  DB::table('users')->where('id', $item->user_id)->first();

            $book =  DB::table('account_details')->where('account_id', $item->id)->sum('total'); //  الاجمالي
            $invoices =  DB::table('invoices')->where('account_id', $item->id)->sum('total'); //  الاجمالي
            $arresteds =  DB::table('arresteds')->where('account_id', $item->id)->sum('total'); // مقبوضات 
            $paids =  DB::table('paids')->where('account_id', $item->id)->sum('total'); // مدفوعات 
            $userTypeRow = DB::table('usertypes')->where('id', $user->user_type)->first();
            $debts = 0;

            $total =  $book  +   $invoices ;

            
            if ($userTypeRow->type_name != 'مورد') {

                $debts = $total -    $arresteds; // الباقي = رصيده المديون - المقبوضات
                $debts =  $debts +   $paids; // الباقي النهائي = المدفوع + الباقي

            } else {

                $debts = $total -   $paids; 
            }

            return [
                'id' => $item->id,
                'account' => $item->account_num,
                'person_name' => $user->user_name,
                'account_user_type' => $userTypeRow  ? $userTypeRow->type_name  : null,
                'total' =>  $total,
                'paid' =>  $paids,
                'arrested' =>  $arresteds,
                'debts' =>  $debts

            ];
        });

        return response()->json($itemsArray);
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

        $res = $data->map(function ($item) {

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

        $data = DB::table('paids')->get();

        $itemsArray = $data->map(function ($item) {

            $user =     DB::table('users')->where('account_id', $item->account_id)->first();


            return [
                'id' => $item->id,
                'total' => $item->total,
                'date' =>  $item->date,
                'notes' =>  $item->notes,
                'account_id' =>  $user->user_name,
            ];
        });

        return response()->json($itemsArray);
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



        $data = $request->all();

        $user = DB::table('users')->where('id', $data['account_id'])->first();

        $res =  DB::table('paids')
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

    public function deletePaid($id)
    {
        $res = DB::table('paids')->where('id', $id)->delete();
        return response()->json($res);
    }


    public function getAllArresteds()
    {

        $data = DB::table('arresteds')->get();

        $itemsArray = $data->map(function ($item) {

            $user =     DB::table('users')->where('account_id', $item->account_id)->first();


            return [
                'id' => $item->id,
                'total' => $item->total,
                'date' =>  $item->date,
                'notes' =>  $item->notes,
                'account_id' =>  $user->user_name,
            ];
        });

        return response()->json($itemsArray);
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

        $data = DB::table('categories')->get();

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

        $data = DB::table('invoices')->get();


        $itemsArray = $data->map(function ($item) {

            $user =     DB::table('users')->where('account_id', $item->account_id)->first();

            return [

                'id' => $item->id,
                'num' => $item->num ? $item->num : 'لا يوجد',
                'invoice_type' => $item->invoice_type == 'purchising' ?  'فاتورة شراء ' : 'فاتورة مبيعات',
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

        $data = $request->all();

        $user = DB::table('users')->where('id', $data['account_id'])->first();

        $res = DB::table('invoices')->insert([

            'invoice_type' => 'purchising',
            'account_id' =>  $user ? $user->account_id : null,
            'total' => $data['total'],
            'date' =>  Carbon::now()->format('Y-m-d H:i:s'),
            'file' => '',



        ]);

        return response()->json($res);
    }

    public function updateInvoice(Request $request, $id)
    {


        $oldInvoice = DB::table('invoices')->where('id', $id)->first();

        Log::info($request->all());

        $data = $request->all();

        $res =  DB::table('invoices')
            ->where('id', $id)
            ->update(

                [
                    'invoice_type' =>  $oldInvoice->invoice_type,
                    'total' => $data['total'],
                    'date' =>  Carbon::now()->format('Y-m-d H:i:s'),
                    'file' => '',

                ]

            );

        return response()->json($res);
    }

    public function deleteInvoice($id)
    {
        $res = DB::table('invoices')->where('id', $id)->delete();
        return response()->json($res);
    }
}
