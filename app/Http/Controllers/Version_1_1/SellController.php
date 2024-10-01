<?php

namespace App\Http\Controllers\Version_1_1;

use App\Models\Sell;
use App\Models\SellDetail;
use App\Models\User;
use App\Http\Requests\StoreSellRequest;
use App\Http\Requests\UpdateSellRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SellController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $data = Sell::orderBy('id', 'desc')->get();

        $res = $data->map(function ($item){
            $user = User::where('id' , $item['user_id']  )->first();

            $item['user_id'] =  $user ? $user->user_name : 'بدون اسم';


            $total = SellDetail::where('sell_id' , $item['id'])->sum('total');

            $item['total'] =  $total ;
            $item['balance'] =   $total  ;
            
            return $item;
        });

        return response()->json($res);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //




    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

      
        //
        $data = $request->all();
        Log::info($data);
        $model = new Sell();
        $model->user_id = array_key_exists('user_id' , $data) ? $data['user_id']  : null ;
        $model->date =  Carbon::now()->format('Y-m-d H:i:s'); ; 
      //  $model->is_paid = array_key_exists('is_paid' , $data) ? $data['is_paid']  : null;
        $model->notes = array_key_exists('notes' , $data) ? $data['notes']  : null;
        $model->save();

        $latest = Sell::orderBy('id','desc')->first();

        // $newSell['id'] =  $latest->id ;
        // $newSell['total'] =  0 ;
        $user = User::where('id' ,  $latest->user_id  )->first();
       // $newSell['user_id'] =  $user ? $user->user_name : 'بدون اسم';
        $latest->user_id =  $user ? $user->user_name : 'بدون اسم';
        // $newSell['date'] =   $latest->date;
        // $newSell['is_paid'] =   $latest->is_paid;
        // $newSell['notes'] =   $latest->notes;

        Log::info(  $latest);
    
        return response()->json($latest);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sell $sell)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sell $sell)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //

        $data =  $request->all();
        $model = Sell::where('id',$id)->first();
        $res = false ;
        if( $model  ){
           //$model->user_id = array_key_exists('user_id' , $data) ? $data['user_id']  :   $model->user_id  ;
            $model->is_paid = array_key_exists('is_paid' , $data) ? $data['is_paid']  :  $model->is_paid ; 
            $model->notes = array_key_exists('notes' , $data) ? $data['notes']  :  $model->notes ; 
            $res =  $model->save();         
        };

        return response()->json($res);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //

        $res = Sell::where('id' , $id)->delete();
        return response()->json( $res);
    }
}
