<?php

namespace App\Http\Controllers\Version_1_1;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    //

    public function getAll()
    {

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




        $res =   Account::with(['user'])->orderBy('id', 'desc')->get();

        $data =  $res->map(function ($item) {

            return [
                'id' => $item->id,
                'account' => $item->account_num,
                'person_name' =>  $item->user->user_name,
                'account_user_type' =>    $item->user->userType->type_name,
                'total' =>  '',
                'invoices' =>  '',
                'book' =>  '',
                'paid' =>  '',
                'arrested' =>  '',
                'debts' =>   ''

            ];
        });


        return response()->json($data);
    }

    public function get($id)
    {

        $data = [];
        return response()->json($data);
    }

    public function create(Request $request)
    {

        $data = [];
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {

        $data = [];
        return response()->json($data);
    }

    public function delete($id)
    {

        $data = [];
        return response()->json($data);
    }
}
