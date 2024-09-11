<?php

namespace App\Http\Controllers\Version_1_1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountDetail;
use Illuminate\Support\Facades\Log;

class AccountDetailsController extends Controller
{
    //

    public function getAll()
    {
        $data = [];
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


        log::info('update');
        $data =  $request->all();
        $model = AccountDetail::where('id',$id)->first();
        $res = false ;
        if( $model  ){

            $model->total = array_key_exists('total' , $data) ? $data['total']  :   $model->total  ;
            $model->quantity = array_key_exists('quantity' , $data) ? $data['quantity']  :  $model->quantity ; 
            $model->price = array_key_exists('price' , $data) ? $data['price']  :  $model->price ; 
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
