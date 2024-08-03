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
 

class ProductsController extends Controller
{
    //

    public function getProductImg($id){

        $imageContent = Product::where('id',$id)->first()->img;
     
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($imageContent);
      //  $mimeType = 'image/jpeg'; // Adjust this based on your actual image type
        return Response::make($imageContent, 200, ['Content-Type' => $mimeType]);
    
    }

    public function getAll()
    {
        $data = [];
        return response()->json($data);
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

            Log::info("Img : ");
            Log::info(  $imageData );

            
        }

        $exchange =   Exchange::where('name' , 'dollar')->first();
        $category =   Category::where('id' ,$data['category_id'] )->first();   

      
        $model = new Product();
        $model->img = $imageData;
        $model->name =  $data['name'];
        $model->code=  $data['code'] ? $data['code'] : 0;
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
