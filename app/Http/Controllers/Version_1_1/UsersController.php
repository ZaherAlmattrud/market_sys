<?php

namespace App\Http\Controllers\Version_1_1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    //

    public function getAll()
    {

        $re = [];
        $data = User::with(['area', 'userType'])->orderBy('id', 'desc')->get();

        $res = $data->map(function ($item)use(& $re) {

           

            // // $dd = [];
            // // $dd['id'] = $item->id;
            // // $dd['user_name'] = $item->user_name;
            // // $dd['user_type'] =  $item->userType ?  $item->userType->type_name : 'غير معرف';
            // // $dd['area'] =   $item->area ? $item->area->name : 'غير معرف';
            // // $dd['account'] = $item->account_id;
            // // $dd['number_in_book'] = $item->number_in_book;

            // $dd = new \stdClass;
            //  $dd->id = $item->id;
            // $dd->user_name = $item->user_name;
            // $dd->user_type =  $item->userType ?  $item->userType->type_name : 'غير معرف';
            // $dd->area =   $item->area ? $item->area->name : 'غير معرف';
            // $dd->account = $item->account_id;
            // $dd->number_in_book = $item->number_in_book;



            array_push($re, $dd);

            return $dd;
        });




        return response()->json($re);
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

        $data = [];
        return response()->json($data);
    }

    public function delete($id)
    {

        $data = [];
        return response()->json($data);
    }
}
