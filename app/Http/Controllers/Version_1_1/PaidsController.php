<?php

namespace App\Http\Controllers\Version_1_1;

use App\Http\Controllers\Controller;
use App\Models\Paid;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaidsController extends Controller
{
    //

    public function getAll()
    {


        $data = [];
        $data =  Paid::with('account.user')->orderBy('id', 'desc')->get();

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


        Log::info('Data : ' . $data['total']);

  

        $paid = Paid::where('id', $id)->first();


        if ($paid) {

            $paid->account_id = $data['account_id'];
            $paid->notes = $data['notes'];
            $paid->total = $data['total'];
            $paid->date =  $data['date'];
            $paid->save();

        }


        return response()->json();
    }

    public function delete($id)
    {

        $data = [];
        return response()->json($data);
    }
}
