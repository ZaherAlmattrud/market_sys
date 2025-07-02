<?php

namespace App\Http\Controllers\Version_1_1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserType;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Response;


class InvoicesController extends Controller
{



    public function  getPhoto($id)
    {



        $invoice = Invoice::where('id', $id)->first();

        return response()->json($invoice);
    }

    //

    public function getAllInvoicesForList()
    {


        $invoices = Invoice::orderBy('id', 'desc')->get(['id']);
        return response()->json($invoices);
    }


    public function getInvoiceImg($id)
    {

        $imageContent = Invoice::where('id', $id)->first()->img;
        $imageContent = base64_decode($imageContent);

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($imageContent);
        // $mimeType = 'image/jpeg'; // Adjust this based on your actual image type
        return Response::make($imageContent, 200, ['Content-Type' => $mimeType]);
    }
    public function getAll(Request $request)
    {

        $search = $request->query('search');

        $query = Invoice::query();

        if ($search) {
            $query->whereHas('account.user', function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%");
            });
        }

        $invoices = $query->orderBy('id', 'desc')->paginate(6);



        // $data = Invoice::orderBy('id', 'desc')->paginate(6);
        return response()->json($invoices);
    }

    public function get($id)
    {

        $data = [];
        return response()->json($data);
    }

    public function create(Request $request)
    {

        $data = $request->all();

        Log::info($data);

        $newRecord['currency'] = array_key_exists('currency', $data) && !empty($data['currency']) ? $data['currency'] : null;
        $newRecord['total'] = array_key_exists('total', $data) && !empty($data['total']) ? $data['total'] : null;
        $newRecord['date'] = array_key_exists('date', $data) && !empty($data['date']) ? $data['date'] : null;


        if (array_key_exists('account_id', $data) && !empty($data['account_id'])) {

            $user = User::where('id', $data['account_id'])->first();
            $account_id =  $user ?  $user->account_id : null;
            $newRecord['account_id'] =   $account_id;
        }


        // ✅ التعامل مع رفع الصورة
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {

            Log::info('accept Photo');
            $photo = $request->file('photo');
            $fileName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('uploads/purcheses'), $fileName);
            $newRecord['photo'] = 'uploads/purcheses/' . $fileName;
        }

        $invoice = Invoice::create($newRecord);

        return response()->json($invoice);

        /*
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
        return response()->json($data); */
    }

    public function update(Request $request, $id)
    {

        $data = $request->all();

        $invoice = Invoice::where('id',  $id)->first();

        Log::info($data);

        $newRecord['total'] = array_key_exists('total', $data) && !empty($data['total']) ? $data['total'] : null;
        $newRecord['date'] = array_key_exists('date', $data) && !empty($data['date']) ? $data['date'] : null;


        if (array_key_exists('account_id', $data) && !empty($data['account_id'])) {

            $user = User::where('id', $data['account_id'])->first();
            $account_id =  $user ?  $user->account_id : null;
            $newRecord['account_id'] =   $account_id;
        }


        // ✅ التعامل مع رفع الصورة
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {

            Log::info('accept Photo');
            $photo = $request->file('photo');
            $fileName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('uploads/purcheses'), $fileName);
            $newRecord['photo'] = 'uploads/purcheses/' . $fileName;
        }

        $invoice = $invoice->update($newRecord);

        return response()->json($invoice);
    }



    public function delete($id)
    {

        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json(['message' => 'الفاتورة غير موجود'], 404);
        }

        $invoice->delete();

        return response()->json(['message' => 'تم حذف الفاتورة بنجاح'], 200);
    }
}
