<?php

namespace App\Http\Controllers\Version_1_1;

use App\Models\SellDetail;
use App\Models\Sell;
use App\Models\User;
use App\Http\Requests\StoreSellDetailRequest;
use App\Http\Requests\UpdateSellDetailRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SellDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($sellId)
    {
        //
        $data['data'] = SellDetail::where('sell_id' , $sellId)->get();
        $data['total'] = SellDetail::where('sell_id' , $sellId)->sum('total');


        $data['userName'] = 'بدون اسم';
        $sell = Sell::where('id' ,  $data['data'][0]['sell_id'])->first();
        $userId =  $sell->user_id ;
        $user = User::where('id' , $userId  )->first();
        if( $user){
            $data['userName'] = $user->user_name ;

        }

      
        return response()->json($data);
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
    public function store(Request $request , $sellId)
    {
        $data = $request->all();
        $model = new SellDetail();
        $model->sell_id =  $sellId ;
        $model->total =  $data['total'] ;
        $model->description =  $data['description'];
        $model->quantity =  $data['quantity'] ;
        $model->price =  $data['price'] ;
        $model->date =  Carbon::now()->format('Y-m-d H:i:s'); ; 
        $model->save();
        return response()->json($model);
    }

    /**
     * Display the specified resource.
     */
    public function show(SellDetail $sellDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SellDetail $sellDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSellDetailRequest $request, SellDetail $sellDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //

        
        $res = SellDetail::where('id' , $id)->delete();
        return response()->json( $res);
    }
}
