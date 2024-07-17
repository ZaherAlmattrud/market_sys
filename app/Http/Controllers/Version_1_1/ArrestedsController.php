<?php

namespace App\Http\Controllers\Version_1_1;

use App\Http\Controllers\Controller;
use App\Models\Arrested;
use Illuminate\Http\Request;

class ArrestedsController extends Controller
{
    //

    public function getAll()
    {
        $data = [];
        $data =  Arrested::with('account.user')->orderBy('id', 'desc')->get();

        $itemsArray = $data->map(function ($item) {



            return [
                'id' => $item->id,
                'total' => $item->total,
                'date' =>  $item->date,
                'notes' =>  $item->notes,
                'account_id' =>  $item->account->user->user_name,
            ];
        });

        return response()->json($itemsArray);
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

        $data = $request->all();;






        $arrested = Arrested::where('id', $id)->first();


        if ($arrested) {
            $arrested->total = $data['total'];
            $arrested->save();
        }


        return response()->json();
    }

    public function delete($id)
    {

        $data = [];
        return response()->json($data);
    }
}
