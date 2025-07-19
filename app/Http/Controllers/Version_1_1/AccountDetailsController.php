<?php

namespace App\Http\Controllers\Version_1_1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountDetail;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;


class AccountDetailsController extends Controller
{
    //

    public function getAll()
    {
        $data = [];
        return response()->json($data);
    }




    public function getAccountDetails($accountId)
    {
        Log::info("id : ");
        Log::info($accountId);

        // بيانات التفاصيل
        $details = AccountDetail::where('account_id', $accountId)->get();

        // مجموع التوتال
        $total = AccountDetail::where('account_id', $accountId)->sum('total');

        // المستخدم المرتبط بالحساب
        $user = User::where('account_id', $accountId)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // نوع المستخدم
        // $userTypeRow = UserType::find($user->user_type);

        // تجهيز الرد
        $res = [];
        $res['total'] = $total;
        $res['account_persion'] = $user->user_name;
        $res['book_number'] = $user->number_in_book;

        // إضافة معرف تسلسلي (identity)
        $id = 1;
        $res['data'] = $details->map(function ($item) use (&$id) {
            $item->identity = $id++;
            return $item;
        });

        return response()->json($res);
    }


    public function get($id)
    {

        $data = [];
        return response()->json($data);
    }

    public function create(Request $request ,$accountId)
    {


        $data = $request->all();
        AccountDetail::create([
            'description' => $data['description'],
            'quantity' => $data['quantity'],
            'total' => $data['total'],
            'price' => $data['price'],
            'date' => Carbon::now(),
            'account_id' => $accountId,
        ]);

        return response()->json(true);
    }

    public function update(Request $request, $id)
    {


        log::info('update');
        $data =  $request->all();
        $model = AccountDetail::where('id', $id)->first();
        $res = false;
        if ($model) {

            $model->total = array_key_exists('total', $data) ? $data['total']  :   $model->total;
            $model->quantity = array_key_exists('quantity', $data) ? $data['quantity']  :  $model->quantity;
            $model->price = array_key_exists('price', $data) ? $data['price']  :  $model->price;
            $res =  $model->save();
        };

        return response()->json($res);
    }

    public function delete($id)
    {

        $data = [];
        return response()->json($data);
    }
}
