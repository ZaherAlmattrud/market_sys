<?php

namespace App\Http\Controllers\Version_1_1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Area;

class AreasController extends Controller
{
    //

    public function getAll()
    {
        $data = Area::orderBy('id', 'desc')->get(['id', 'name']);
        return response()->json($data);
    }

    public function get($id)
    {

        $data = [];
        return response()->json($data);
    }

    public function create(Request $request)
    {

        $data = $request->all();
        $area = new Area();
        $area->name = $data['name'];
        $res = $area->save();

        return response()->json($res);
    }

    public function update(Request $request, $id)
    {


        $data = $request->all();

        $area = Area::find($id);

        if ($area) {
            $area->name = $data['name'];
            $area->save();
            return response()->json(true);
        }

        return response()->json(false, 404);
    }

    public function delete($id)
    {

        $area =  Area::find($id);

        if ($area) {
            $area->delete();
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }
}
