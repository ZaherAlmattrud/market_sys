<?php

namespace App\Http\Controllers\Version_1_1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Version_1_1\AccountController;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    //


    private $accountController;

    function __construct(AccountController $accountController)
    {

        $this->accountController = $accountController;
    }

    public function getAll()
    {

        $data = User::with(['area', 'userType'])->orderBy('id', 'desc')->get();

        $res = $data->map(function ($item) {

            return [
                'id' => $item->id,

                'user_name' => $item->user_name,

                'user_type_name' => $item->userType ?   $item->userType->type_name : 'غير محدد',

                'user_type' =>   $item->user_type,

                'area_name' =>  $item->area  ? $item->area->name : 'غير محدد',

                'area_id' =>  $item->area_id,

                'account_id' => $item->id,

                'number_in_book' => $item->number_in_book ? $item->number_in_book : 'لايوجد',

                'mobile' => $item->mobile,

                'balance' => NULL // $data['total'] ,  
            ];
        });







        return response()->json($res);
    }

    public function getLatest()
    {

        $item = User::orderBy('id', 'desc')->first();
        return $item;
    }

    public function get($id)
    {

        $data = [];
        return response()->json($data);
    }


    public function getMaxAccountID()
    {

        $id = 1; // if data table null
        $item = Account::orderBy('id', 'desc')->first();
        if ($item)
            $id = $item->id + 1;
        return  $id;
    }

    public function create(Request $request)
    {



        DB::beginTransaction();
        $user = null;

        try {
            // Perform your database operations here
            $data = $request->all();
            $model = new User();
            $model->user_name =  array_key_exists('user_name', $data) ? $data['user_name']  : null;
            $model->user_type =  array_key_exists('user_type', $data) ? $data['user_type']  : null;
            $model->area_id =  array_key_exists('area_id', $data) ? $data['area_id']  : null;
            $model->number_in_book =  array_key_exists('number_in_book', $data) ? $data['number_in_book']  : null;
            $model->mobile =  array_key_exists('mobile', $data) ? $data['mobile']  : null;
            $model->account_id = $this->getMaxAccountID() + 1;
            $model->save();

            $user = $this->getLatest();
            $newModel = new Account();
            $newModel->user_id = $user->id;
            $newModel->save();

            // Commit the transaction if everything goes well
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();

            // Optionally, log the error or handle it
            throw $e;
        }

        $res = User::with(['area', 'userType'])->orderBy('id', 'desc')->first();
        $res->user_type_name = $res->userType ?   $res->userType->type_name : 'غير محدد';
        $res->area_name = $res->area ?   $res->area->name : 'غير محدد';
        $res->number_in_book =    $res->number_in_book ?  $res->number_in_book : 'لايوجد';


        return response()->json($res);
    }

    public function update(Request $request, $id)
    {

        $data =  $request->all();
        Log::info($data);
        $model = User::where('id', $id)->first();
        $res = false;
        if ($model) {
            $model->user_name = array_key_exists('user_name', $data) ? $data['user_name']  :  $model->user_name;
            $model->mobile = array_key_exists('mobile', $data) ? $data['mobile']  :  $model->mobile;
            $model->user_type = array_key_exists('user_type', $data) ? $data['user_type']  :  $model->user_type;
            $model->number_in_book = array_key_exists('number_in_book', $data) ? $data['number_in_book']  :  $model->number_in_book;
            $model->area_id = array_key_exists('area_id', $data) ? $data['area_id']  :  $model->area_id;
            $res =  $model->save();
        };

        $user = User::with(['area', 'userType'])->where('id', $id)->first();
        $user->user_type_name = $user->userType ?   $user->userType->type_name : 'غير محدد';
        $user->area_name = $user->area ?   $user->area->name : 'غير محدد';
        $user->number_in_book =    $user->number_in_book ?  $user->number_in_book : 'لايوجد';


        return response()->json($user);
    }



    public function delete($id)
    {

        $res = User::where('id', $id)->delete();
        return response()->json($res);
    }
}
