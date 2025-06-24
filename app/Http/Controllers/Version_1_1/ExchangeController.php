<?php

namespace App\Http\Controllers\Version_1_1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Exchange;

class ExchangeController extends Controller
{
    //

    public function getExchange()
    {

        $dollar_now = Exchange::where('name', 'dollar')->first()->value;
        return response()->json($dollar_now);
    }

    public function getAll()
    {

        $data =  Exchange::all();
        return response()->json($data);
    }



    public function create(Request $request)
    {

        $data = $request->all();




        // return response()->json($res);
    }

    public function update(Request $request, $id)
    {


        $data = $request->all();;
        $data['value'] = array_key_exists('value', $data)  && !empty($data['value']) ? $data['value'] : null;
        $exchange =   Exchange::where('id', $id)->first();
        if ($exchange) {
            $exchange->value =  $data['value'];
            $exchange->save();
        }


        return response()->json();
    }

    public function delete($id)
    {




        //  return response()->json( $res);
    }
}
