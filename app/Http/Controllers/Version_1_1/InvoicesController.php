<?php

namespace App\Http\Controllers\Version_1_1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User ;
use App\Models\UserType ;
use App\Models\Invoice ;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Response;
 

class InvoicesController extends Controller
{
    //


    public function getInvoiceImg($id){

        $imageContent = Invoice::where('id',$id)->first()->img;
        $imageContent = base64_decode( $imageContent);
 
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($imageContent);
        // $mimeType = 'image/jpeg'; // Adjust this based on your actual image type
        return Response::make($imageContent, 200, ['Content-Type' => $mimeType]);
    
    }
    public function getAll()
    {
        $data = [];

        $data = Invoice::orderBy('id', 'desc')->get();


        $itemsArray = $data->map(function ($item) {

            $user =     User::where('account_id', $item->account_id)->first();

            return [

                'id' => $item->id,
                'file_url' =>  $item->file_url ?   $item->file_url  : null,
                'num' => $item->num ? $item->num : 'لا يوجد',
                'invoice_type' => $item->invoice_type,
                'account_id' =>  $user ? $user->user_name : null,
                'total' => $item->total,
                'date' => $item->date,
                'file' => $item->file,


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

        $data =  $request->all();
        $user = User::where('id' ,$data['account_id'] )->first();
        $userTypeId = $user ? $user->user_type : null;

        $userType =  UserType::where('id' ,  $userTypeId)->first();// DB::table('usertypes')->where('id',  $userTypeId)->first();

        $InvoiceType =   $userType->type_name == 'مورد' ? 'شراء' : 'بيع';
 
        $imageData = null ;
        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $imageData = file_get_contents($image->getRealPath());
            $imageData = base64_encode( $imageData );

            Log::info("Img : ");
         //   Log::info(  $imageData );

            
        }

        $model = new Invoice();
        $model->img = $imageData;
        $model->invoice_type = $InvoiceType;
        $model->account_id =  $user ? $user->account_id : null;
        $model->total = $data['total'] ? $data['total'] : '0';
        $model->num = $data['num'];
        $model->date =  Carbon::now()->format('Y-m-d H:i:s');
        $model->save();
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {

        Log::info("updateeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee");
        $data =  $request->all();
        Log::info(    $data );
        $model = Invoice::find($id);
      
        $res = false ;
        if( $model  ){
            // $model->total = $data['total'];
            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $imageData = file_get_contents($image->getRealPath());
                $imageData = base64_encode( $imageData );
                Log::info(     $imageData );
                $model->img = $imageData;     
            }
            $res =  $model->save();         
        }

        return response()->json($res);
    }

    public function delete($id)
    {

        $data = [];
        return response()->json($data);
    }
}
