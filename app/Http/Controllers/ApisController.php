<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApisController extends Controller
{
    //

    public function  getAllAreas()
    {

        $data = DB::table('areas')->get();

        $itemsArray = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        });

        return response()->json($itemsArray);
    }



    public function createArea(Request $request)
    {

        $data = $request->all();

        $res = DB::table('areas')->insert([
            'name' => $data['name']
        ]);





        return response()->json($$res);
    }

    public function deleteArea($id)
    {

        $res = DB::table('areas')->where('id', $id)->delete();
        return response()->json($res);
    }

    public function updateArea(Request $request, $id)
    {

        $data = $request->all();
        Log::info('Update');
        Log::info('Data');
        Log::info($data);

        $res =  DB::table('areas')
            ->where('id', $id)
            ->update(['name' => $data['name']]);

        return response()->json($res);
    }




    public function  getAllUserTypes()
    {

        $data = DB::table('usertypes')->get();

        $itemsArray = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->type_name,
            ];
        });

        return response()->json($itemsArray);
    }


    public function getAllUsers()
    {

        $data = DB::table('users')->get();

        $itemsArray = $data->map(function ($item) {

            $userType =  DB::table('usertypes')
                ->where('id', $item->user_type)
                ->get('type_name')->first();




            $area =  DB::table('areas')
                ->where('id', $item->area_id)
                ->get(['name'])->first();

            return [
                'id' => $item->id,
                'user_name' => $item->user_name,
                'user_type' =>   $userType->type_name,
                'area' =>  $area->name,
                'account' => $item->id,
            ];
        });

        return response()->json($itemsArray);
    }

    public function createUser(Request $request)
    {

        $data = $request->all();

        Log::info($data);

        $res = DB::table('users')->insert(
            [
                'user_name' => $data['user_name'],
                'user_type' => $data['user_type'],
                'area_id' => $data['area'],
            ]
        );


        $user =  DB::table('users')->orderBy('id', 'desc')->first();

        // add new account to user
        $account = DB::table('accounts')->insert(
            [
                'user_id' =>  $user->id,

            ]
        );



        return response()->json($res);
    }

    public function updateUser(Request $request, $id)
    {

        $data = $request->all();


        $userTypeId = null;
        $areaId = null;




        if (gettype(($data['area'])) == 'integer') {

            $areaId = $data['area'];
        } else {

            $area =  DB::table('areas')
                ->where('name', $data['area'])
                ->get(['id'])->first();

            $areaId =  $area->id;
        }

        if (gettype(($data['user_type'])) == 'integer') {



            $userTypeId = $data['user_type'];
        } else {

            $userType =  DB::table('usertypes')
                ->where('type_name', $data['user_type'])
                ->get(['id'])->first();


            $userTypeId =  $userType->id;
        };


        $res =  DB::table('users')
            ->where('id', $id)
            ->update([
                'user_name' => $data['user_name'],
                'user_type' =>     $userTypeId,
                'area_id' =>   $areaId,
            ]);



        return response()->json($res);
    }


    public function deleteUser($id)
    {

        $res = DB::table('users')->where('id', $id)->delete();
        return response()->json($res);
    }
}
