<?php

namespace App\Http\Controllers\Version_1_1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exchange;
use App\Models\Category;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Response ;
use DB ;
 

class ProductsController extends Controller
{
    //

    public function getProductImg($id){

        $imageContent = Product::where('id',$id)->first()->img;
        $imageContent = base64_decode( $imageContent);
     
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($imageContent);
      //  $mimeType = 'image/jpeg'; // Adjust this based on your actual image type
        return Response::make($imageContent, 200, ['Content-Type' => $mimeType]);
    
    }

    public function getAll()
    {
        $data = [];

        
         $data =  Product::orderBy('id', 'desc')->get();

        $itemsArray = $data->map(function ($item) {

           
            $category =     DB::table('categories')->where('id', $item->category_id)->first();
            $exchange =     DB::table('exchange')->first();
            $suppler = null ;
            $invoice = null ;
            $invoice =     DB::table('invoices')->where('id', $item->invoice_id)->first();
            if(   $invoice )
             $suppler =    DB::table('users')->where('account_id' , $invoice->account_id )->first();

            return [
                'id' => $item->id,
                'name' => $item->name,
                'code' => $item->code,
                'date' => $invoice ? $invoice->date :  $item->date ,
                'price'     => $item->price,
                'price_after_descount'     =>  $category ? ($item->price) - ($category->descount * $item->price) : $item->price,
                'notes' => $item->notes,
                'sell' => $item->sell,
                'price_in_dollar' => $item->price_in_dollar,
                //'img' => $item->img,
                'invoice_id' =>     $suppler ?  $suppler->user_name  : $item->invoice_id,
               // 'suppler' => $item->invoice_id,
                'category_id' => $category ?  $category->name : null,
                'updatingPrice' => $item->price_in_dollar *   $exchange->value,
            ];
        });

        return response()->json($itemsArray);

       // return response()->json($data);
    }

    public function get($id)
    {

        $data = [];
        return response()->json($data);
    }

    public function create(Request $request)
    {

        $data = $request->all();
 
 
        $imageData = null ;
        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $imageData = file_get_contents($image->getRealPath());
            $imageData = base64_encode($imageData);


            
        }

        $exchange =   Exchange::where('name' , 'dollar')->first();
        $category =   Category::where('id' ,$data['category_id'] )->first();   

       // $code = array_key_exists('code' , $data) ? $data['code']  : 0 ;
        $model = new Product();
        $model->img = $imageData;
        $model->name =  $data['name'];
        $model->code=    $data['code'] ?  $data['code'] : 0 ;
        $model->price    = $data['price'];
         $model->notes =  null;
         $model->price_in_dollar = $data['pricr_in_doller'];
       $model->sell=  $data['sell'];
     
          $model->invoice_id =  $data['invoice_id'] ? $data['invoice_id'] : 0;
          $model->category_id =  $data['category_id'] ? $data['category_id'] : 4 ;
         $model->date = Carbon::now()->format('Y-m-d H:i:s');
      
        
         $model->price_after_descount = $category ? ($data['price']) - ($category->descount *  $data['price']) :  $data['price'];
          $model->price_in_dollar =   $data['price'] /   $exchange->value;
    
        $model->save();
        return response()->json($data);


        
    }

    public function update(Request $request, $id)
    {


     
        $data =  $request->all();
        $model = Product::where('id',$id)->first();
        $res = false ;

        $imageData = null ;
      

        if( $model  ){
           //$model->user_id = array_key_exists('user_id' , $data) ? $data['user_id']  :   $model->user_id  ;
           $model->name = array_key_exists('name' , $data) ? $data['name']  :  $model->name ; 
            $model->sell = array_key_exists('sell' , $data) ? $data['sell']  :  $model->sell ; 
            $imageData = null ;
            if ($request->hasFile('file')) {

                Log::info("=======================");
                $image = $request->file('file');
                $imageData = file_get_contents($image->getRealPath());
                $imageData = base64_encode($imageData); 
               
            }

            $model->img =    $imageData ?   $imageData :  $model->img ; 
            $res =  $model->save();         
        };

        return response()->json($res);
    }

    public function delete($id)
    {

        $data = [];
        return response()->json($data);
    }
}
