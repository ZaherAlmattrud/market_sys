<?php

namespace App\Http\Controllers\Version_1_1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Currency;
use Illuminate\Support\Facades\Log;

class ExchangeController extends Controller
{
    //

    public function getExchange()
    {

        $dollar_now = Currency::where('code', 'USD')->first()->value;
        return response()->json($dollar_now);
    }

    public function getAll()
    {

        $data =  Currency::all();
        return response()->json($data);
    }



    public function create(Request $request)
    {

        $data = $request->all();
        Log::info($data);

        Currency::create($data);




        return response()->json();
    }

    public function update(Request $request, $id)
    {


        $data = $request->all();;
        $data['value'] = array_key_exists('value', $data)  && !empty($data['value']) ? $data['value'] : null;
        $data['code'] = array_key_exists('code', $data)  && !empty($data['code']) ? $data['code'] : null;
        $exchange =   Currency::where('id', $id)->first();
        if ($exchange) {
            $exchange->value =  $data['value'];
            $exchange->code =  $data['code'];
            $exchange->save();
        }


        return response()->json();
    }

    public function delete($id)
    {


        $exchange = Currency::where('id', $id)->first();
        if ($exchange) {

            $exchange->delete();
        }

        return response()->json();
    }
}
