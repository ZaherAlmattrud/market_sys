<?php

namespace App\Http\Controllers\Version_1_1;

use App\Http\Controllers\Controller;
use App\Models\UserType;
use Illuminate\Http\Request;

class UserTypesController extends Controller
{
    //
    public function getAll()
    {
        $data = UserType::all();
        return response()->json($data);
    }

    public function getById($id){

        $item = UserType::find($id);
        return $item;
    }

    public function get($id)
    {

        $data = [];
        return response()->json($data);
    }


    public function getLatest(){

        $item = UserType::orderBy('id', 'desc')->first();
        return $item;
    }

    public function create(Request $request)
    {

        $data = $request->all();
        $model = new UserType();
        $model->type_name =  array_key_exists('type_name' , $data) ? $data['type_name']  : null ;
        $model->save();
        $latest = $this->getLatest();
        return response()->json($latest);
    }

    public function update(Request $request, $id)
    {

        $data =  $request->all();
        $model = UserType::where('id', $id)->first();
        $res = false;
        if ($model) {
            $model->type_name = array_key_exists('type_name', $data) ? $data['type_name']  :  $model->type_name;
            $res =  $model->save();
        };

        return response()->json($res);
    }

    public function delete($id)
    {
        $res = UserType::where('id', $id)->delete();
        return response()->json($res);
    }
}
