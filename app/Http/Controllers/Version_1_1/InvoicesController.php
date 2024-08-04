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
     
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($imageContent);
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

        $data =  $request->all();
        $user = User::where('id' ,$data['account_id'] )->first();
        $userTypeId = $user ? $user->user_type : null;

        $userType =  UserType::where('id' ,  $userTypeId)->first();// DB::table('usertypes')->where('id',  $userTypeId)->first();

        $InvoiceType =   $userType->type_name == 'مورد' ? 'شراء' : 'بيع';
 
        $imageData = null ;
        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $imageData = file_get_contents($image->getRealPath());

            Log::info("Img : ");
            Log::info(  $imageData );

            
        }

        $model = new Invoice();
        $model->img = $imageData;
        $model->invoice_type = $InvoiceType;
        $model->account_id =  $user ? $user->account_id : null;
        $model->total = $data['total'] ? $data['total'] : '0';
        $model->num = $data['num'];
        $model->date =  Carbon::now()->format('Y-m-d H:i:s');
        $model->save();
        return response()->json();
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
