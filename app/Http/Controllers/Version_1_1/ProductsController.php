<?php

namespace App\Http\Controllers\Version_1_1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exchange;
use App\Models\Category;
use App\Models\Product;
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

            $invoice =     DB::table('invoices')->where('id', $item->invoice_id)->first();
            $category =     DB::table('categories')->where('id', $item->category_id)->first();
            $exchange =     DB::table('exchange')->first();



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
                'invoice_id' => $item->invoice_id,
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

        $code = array_key_exists('code' , $data) ? $data['code']  : 0 ;
        $model = new Product();
        $model->img = $imageData;
        $model->name =  $data['name'];
        $model->code=   $code  ;
        $model->price    = $data['price'];
         $model->notes =  null;
         $model->price_in_dollar = $data['pricr_in_doller'];
       $model->sell=  $data['sell'];
     
          $model->invoice_id =  $data['invoice_id'];
          $model->category_id =  $data['category_id'];
         $model->date = Carbon::now()->format('Y-m-d H:i:s');
      
        
         $model->price_after_descount = $category ? ($data['price']) - ($category->descount *  $data['price']) :  $data['price'];
          $model->price_in_dollar =   $data['price'] /   $exchange->value;
    
        $model->save();
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
