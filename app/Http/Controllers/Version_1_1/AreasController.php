<?php

namespace App\Http\Controllers\Version_1_1;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;

class AreasController extends Controller
{
    //
    public function getAll()
    {
        $data = Area::all();
        return response()->json($data);
    }

    public function get($id)
    {

        $data = [];
        return response()->json($data);
    }

    public function getById($id){

        $item = Area::find($id);
        return $item;
    }

    public function getLatest(){

        $item = Area::orderBy('id', 'desc')->first();
        return $item;
    }


    public function create(Request $request)
    {

        $data = $request->all();
        $model = new Area();
        $model->name = array_key_exists('name' , $data) ? $data['name']  :  null ;
        $model->save();
        $item = $this->getLatest();
        return response()->json( $item);
    }

    public function update(Request $request, $id)
    {

        $data =  $request->all();
        $model = Area::where('id', $id)->first();
        $res = false;
        if ($model) {
            $model->name = array_key_exists('name', $data) ? $data['name']  :  $model->name;
            $res =  $model->save();
        };

        return response()->json($res);
    }

    public function delete($id)
    {

        $res = Area::where('id', $id)->delete();
        return response()->json($res);
    }
}
