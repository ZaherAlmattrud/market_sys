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

      log::info("Img Type : ".$mimeType);
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
                'suppler'=> $suppler ?  $suppler->user_name  : 'غير معروف',
                'price_in_dollar' => $item->price_in_dollar,
                //'img' => $item->img,
                'invoice_id' =>   $item->invoice_id, //  $suppler ?  $suppler->user_name  : 
               // 'suppler' => $item->invoice_id,
                'category_id' => $item->category_id,
                'category_name' => $category ?  $category->name : null,
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

        Log::info("payload");
        Log::info($data);


        $model = null ;

        //Add New Item 

        if ( $data['id'] == 0 ){


            Log::info("New");

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
            $model->price    = $data['price'] ? $data['price'] : 0 ;
             $model->notes =  $data['notes'] ? $data['notes'] : null;
             $model->price_in_dollar = $data['pricr_in_doller'];
           $model->sell=  $data['sell'];
         
              $model->invoice_id =  $data['invoice_id'] ? $data['invoice_id'] : 0;
              $model->category_id =  $data['category_id'] ? $data['category_id'] : 4 ;
             $model->date = Carbon::now()->format('Y-m-d H:i:s');
          
            
             $model->price_after_descount = $category ? ($data['price']) - ($category->descount *  $data['price']) :  $data['price'];
              $model->price_in_dollar =   $data['price'] /   $exchange->value;
        
            $model->save();



        }else{

              //Add New Item Update

              
        $model = Product::where('id',$data['id'] )->first();
        $res = false ;

        if( $model  ){

            $imageData = null ;
            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $imageData = file_get_contents($image->getRealPath());
                $imageData = base64_encode($imageData);
    
    
                
            }

            $model->img = $imageData;
           $model->name = $model->name ; 
           $model->price =  $data['price']    ; 
           $model->sell =  $data['sell']    ;
           $model->invoice_id =   $data['invoice_id']    ; 
           $model->code =   $data['code']  ?    $data['code'] : 0 ; 
           $model->category_id =   $data['category_id']    ; 
           $model->notes =  $data['notes'] ? $data['notes'] : null;
           $res =  $model->save();         

        };
 
       


        }

        $category =     DB::table('categories')->where('id', $model->category_id)->first();
        $exchange =     DB::table('exchange')->first();
        $suppler = null ;
        $invoice = null ;
        $invoice =     DB::table('invoices')->where('id', $model->invoice_id)->first();
        if(   $invoice )
         $suppler =    DB::table('users')->where('account_id' , $invoice->account_id )->first();


      
        return response()->json([

              'id'=> $model->id ,
              'name' => $model->name,
              'code' => $model->code,
              'date' => $invoice ? $invoice->date :  $model->date ,
              'price'     => $model->price,
              'price_after_descount'     =>  $category ? ($model->price) - ($category->descount * $model->price) : $item->price,
              'notes' => $model->notes,
              'sell' => $model->sell,
              'suppler'=> $suppler ?  $suppler->user_name  : 'غير معروف',
              'price_in_dollar' => $model->price_in_dollar,
              'invoice_id' =>   $model->invoice_id,  
              'category_id' => $model->category_id,
              'category_name' => $category ?  $category->name : null,
              'updatingPrice' => $model->price_in_dollar *   $exchange->value,


        ]);


        
    }

    public function update(Request $request, $id)
    {



        Log::info("update");
       
     
        $data =  $request->all();

        Log::info($data);

        $model = Product::where('id',$id)->first();
        $res = false ;


     //   $category =     DB::table('categories')->where('id', $data['category_id'] )->first();
      

        if( $model  ){

            // $imageData = null ;

            // if ($request->hasFile('file')) {

            //     $image = $request->file('file');
            //     $imageData = file_get_contents($image->getRealPath());
            //     $imageData = base64_encode($imageData);
            //     $model->img = $imageData;
            // }

        
           $model->name = array_key_exists('name' , $data) ? $data['name']  :  $model->name ; 
           $model->notes =  array_key_exists('notes' , $data) ? $data['notes']  :  $model->notes ; 
           $model->price = array_key_exists('price' , $data) ? $data['price']  :  $model->price ; 
           $model->sell = array_key_exists('sell' , $data) ? $data['sell']  :  $model->sell ;
           $model->invoice_id = array_key_exists('invoice_id' , $data) ? $data['invoice_id']  :  $model->invoice_id ; 
           $model->code = array_key_exists('code' , $data) ? $data['code']  :  $model->code ; 
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
