<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApisController extends Controller
{
    //

    public function  getAllAreas(){

        $data = DB::table('areas')->get();
        return response()->json($data);
    }

    public function createArea(Request $request){

        $data = $request->all();

        Log::info('Create');
        Log::info('Data');
        Log::info(  $data );

        $res = DB::table('areas')->insert([
            'name' => $data['name']
        ]);

        return response()->json( $res);
    }

    public function deleteArea($id){

        $res = DB::table('areas')->where('id', $id)->delete(); 
        return response()->json( $res);

    }

    public function updateArea(Request $request, $id){

        $data = $request->all();
        Log::info('Update');
         Log::info('Data');
         Log::info(  $data );

        $res =  DB::table('areas')
        ->where('id', $id)
        ->update(['name' => $data['name']]);

        return response()->json( $res);

    }


}
