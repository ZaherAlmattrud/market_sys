<?php

namespace App\Http\Controllers\Version_1_1;

use App\Http\Controllers\Controller;
use App\Models\Day;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DayController extends Controller
{
    //

    public function getAll()
    {


        $data = Day::orderBy('id', 'desc')->get();

        $res = $data->map(function ($item) {


            return  [

                'id' =>  $item['id'],
                'day' =>  $item['day'],
                'arresteds' => $item['arresteds'],
                'paids' => $item['paids'],
                'box' => $item['box'],
                'difference' =>      $item['difference'],


            ];
        });


        return response()->json($res);
    }

    public function get($id)
    {

        $data = [];
        return response()->json($data);
    }

    public function create(Request $request)
    {

        $data = $request->all();


        $debts = $data['arresteds'] - $data['paids'];
        $difference =  $data['box'] -  $debts;

        $res = Day::create([

            'day' => Carbon::now()->format('Y-m-d'),
            'arresteds' => $data['arresteds'],
            'paids' => $data['paids'],
            'box' => $data['box'],
            'difference' =>      $difference,
        ]);

        return response()->json($res);
    }

    public function update(Request $request, $id)
    {

        $data = $request->all();;


        

  

        $day = Day::where('id', $id)->first();

        $debts = $data['arresteds'] - $data['paids'];
        $difference =  $data['box'] -  $debts;


        if ($day) {
            $day->arresteds = $data['arresteds'];
            $day->paids = $data['paids'];
            $day->box = $data['box'];
            $day->difference = $difference;
            $day->save();
        }


        return response()->json();
    }

    public function delete($id)
    {

        

        $res = Day::where('id' , $id)->delete();
        return response()->json( $res);
    }
}
