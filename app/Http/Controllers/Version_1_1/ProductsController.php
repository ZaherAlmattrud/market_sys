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
use Response;
use DB;


class ProductsController extends Controller
{
    //

    public function getProductImg($id)
    {

        $imageContent = Product::where('id', $id)->first()->img;
        $imageContent = base64_decode($imageContent);

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($imageContent);
        //  $mimeType = 'image/jpeg'; // Adjust this based on your actual image type

        log::info("Img Type : " . $mimeType);
        return Response::make($imageContent, 200, ['Content-Type' => $mimeType]);
    }


    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Product::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%");
        }

        $products = $query->orderBy('id', 'desc')->paginate(6);

        $dollar_now = Exchange::where('name', 'dollar')->first()->value;

        $products->getCollection()->transform(function ($product) use ($dollar_now) {


            $category = Category::where('id', $product->category_id)->first();
            $descount =  $category && $category->descount ? $category->descount : 0;



            $price_in_dollar = $product->price_in_dollar - ($product->price_in_dollar *    $descount);

            $price_in_Sp = $product->price_in_sp - ($product->price_in_sp *  $descount);

            $dynamic_sell_sp = $product->sell_in_dollar * $dollar_now;
            $dynamic_sell_dollar =   $product->sell_in_dollar;


            return [
                'id' => $product->id,
                'name' => $product->name,
                'fix_price_in_dollar' =>  $price_in_dollar, // after descount
                'fix_price_in_sp' => $price_in_Sp, // after descount
                'dynamic_price_in_sp' => $product->price_in_dollar * $dollar_now,
                'dynamic_sell' =>  ceil((float)$dynamic_sell_sp) . "  ل.س / " .    number_format((float)$dynamic_sell_dollar, 2) . " دولار",

            ];
        });

        return response()->json($products);
    }


    public function getAll(Request $request)
    {



        return $this->index($request);


        /*
        $data = [];

        
         $data =  Product::orderBy('id', 'desc')->get();

        $itemsArray = $data->map(function ($item) {

           
            $category =    null ; // DB::table('categories')->where('id', $item->category_id)->first();
            $exchange =    null ; // DB::table('exchange')->first();
            $suppler = null ;
            $invoice = null ;
            $invoice =    null ; // DB::table('invoices')->where('id', $item->invoice_id)->first();
            if(   $invoice )
             $suppler =  null ;//  DB::table('users')->where('account_id' , $invoice->account_id )->first();

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
                'updatingPrice' => null   // $item->price_in_dollar *   $exchange->value,
            ];
        });

        return response()->json($itemsArray);

       // return response()->json($data);

       */
    }

    public function get($id)
    {

        $data = [];
        return response()->json($data);
    }

    public function save(Request $request)
    {

        $exchangeRate   = Exchange::where('name', 'dollar')->first()->value; // ثابت مؤقت، يفضل قراءته من config أو DB
        Log::info($exchangeRate);
        $data = $request->all();

        Log::info($data);


        if ($request->filled('price_in_dollar') && !$request->filled('price_in_sp')) {
            Log::info('حُسب السعر بالليرة من الدولار');

            $newRecord['price_in_dollar'] = $request->price_in_dollar;
            $newRecord['price_in_sp'] = $request->price_in_dollar * $exchangeRate;
        } elseif ($request->filled('price_in_sp') && !$request->filled('price_in_dollar')) {
            Log::info('حُسب السعر بالدولار من الليرة');

            $newRecord['price_in_sp'] = $request->price_in_sp;
            $newRecord['price_in_dollar'] = $request->price_in_sp / $exchangeRate;
        } else {
            Log::info('كلا السعرين موجودين، لا يتم الحساب');


            $newRecord['price_in_sp'] = $request->price_in_sp;
            $newRecord['price_in_dollar'] = $request->price_in_dollar;
        }

        $newRecord['name'] = array_key_exists('name', $data) && !empty($data['name']) ? $data['name'] : null;
        $newRecord['code'] = array_key_exists('code', $data) && !empty($data['code']) ? $data['code'] : null;
        $newRecord['invoice_id'] = array_key_exists('invoice_id', $data) && !empty($data['invoice_id']) ? $data['invoice_id'] : null;
        $newRecord['category_id'] = array_key_exists('category_id', $data) && !empty($data['category_id']) ? $data['category_id'] : null;
        $newRecord['sell_in_sp'] = array_key_exists('sell_in_sp', $data) && !empty($data['sell_in_sp']) ? $data['sell_in_sp'] : null;
        $newRecord['sell_in_dollar'] =  $newRecord['sell_in_sp'] /  $exchangeRate;
        $newRecord['profit'] =  $newRecord['sell_in_dollar'] - $newRecord['price_in_dollar'];
        $invoice =  Invoice::where('id',  $newRecord['invoice_id'])->first();
        $newRecord['date'] =  $invoice ?  $invoice->date : null;
        $newRecord['category_id'] = array_key_exists('category_id', $data) && !empty($data['category_id']) ? $data['category_id'] : null;



        // ✅ التعامل مع رفع الصورة
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {

            Log::info('accept Photo');
            $photo = $request->file('photo');
            $fileName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('uploads/products'), $fileName);
            $newRecord['photo'] = 'uploads/products/' . $fileName;
        }

        Log::info($newRecord);

        // متابعة عملية الحفظ
        $product = Product::create($newRecord);

        return response()->json($product);
    }

    public function create(Request $request)
    {

        $data = $request->all();

        Log::info("payload");
        Log::info($data);


        $model = null;
        $exchange =   Exchange::where('name', 'dollar')->first();

        //Add New Item 

        if ($data['id'] == 0) {


            Log::info("New");

            $imageData = null;
            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $imageData = file_get_contents($image->getRealPath());
                $imageData = base64_encode($imageData);
            }


            $category =   Category::where('id', $data['category_id'])->first();

            // $code = array_key_exists('code' , $data) ? $data['code']  : 0 ;
            $model = new Product();
            $model->img = $imageData;
            $model->name =  $data['name'];
            $model->code =    $data['code'] ?  $data['code'] : 0;
            $model->price    = $data['price'] ? $data['price'] : 0;
            $model->notes =  $data['notes'] ? $data['notes'] : '';
            $model->price_in_dollar = $data['pricr_in_doller'];
            $model->sell =  $data['sell'];

            $model->invoice_id =  $data['invoice_id'] ? $data['invoice_id'] : 0;
            $model->category_id =  $data['category_id'] ? $data['category_id'] : 4;
            $model->date = Carbon::now()->format('Y-m-d H:i:s');

            // ✅ التعامل مع رفع الصورة
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {

                Log::info('accept Photo');
                $photo = $request->file('photo');
                $fileName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('uploads/products'), $fileName);
                $newRecord['photo'] = 'uploads/products/' . $fileName;
            }


            $model->price_after_descount = $category ? ($data['price']) - ($category->descount *  $data['price']) :  $data['price'];
            $model->price_in_dollar =   $data['price'] /   $exchange->value;

            $model->save();
        } else {

            //Add New Item Update


            $model = Product::where('id', $data['id'])->first();
            $res = false;

            if ($model) {

                $imageData = null;
                if ($request->hasFile('file')) {
                    $image = $request->file('file');
                    $imageData = file_get_contents($image->getRealPath());
                    $imageData = base64_encode($imageData);
                }

                $model->img = $imageData;
                $model->name = $model->name;
                $model->price =  $data['price'];
                $model->price_in_dollar =   $data['price'] /   $exchange->value;
                $model->sell =  $data['sell'];
                $model->invoice_id =   $data['invoice_id'];
                $model->code =   $data['code']  ?    $data['code'] : 0;
                $model->category_id =   $data['category_id'];
                $model->notes =   array_key_exists('notes', $data) ? $data['notes'] : '';
                $res =  $model->save();
            };
        }

        $category =     DB::table('categories')->where('id', $model->category_id)->first();
        $exchange =     DB::table('exchange')->first();
        $suppler = null;
        $invoice = null;
        $invoice =     DB::table('invoices')->where('id', $model->invoice_id)->first();
        if ($invoice)
            $suppler =    DB::table('users')->where('account_id', $invoice->account_id)->first();



        return response()->json([

            'id' => $model->id,
            'name' => $model->name,
            'code' => $model->code,
            'date' => $invoice ? $invoice->date :  $model->date,
            'price'     => $model->price,
            'price_after_descount'     =>  $category ? ($model->price) - ($category->descount * $model->price) : $item->price,
            'notes' => $model->notes,
            'sell' => $model->sell,
            'suppler' => $suppler ?  $suppler->user_name  : 'غير معروف',
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

        $model = Product::where('id', $id)->first();
        $res = false;


        //   $category =     DB::table('categories')->where('id', $data['category_id'] )->first();


        if ($model) {

            // $imageData = null ;

            // if ($request->hasFile('file')) {

            //     $image = $request->file('file');
            //     $imageData = file_get_contents($image->getRealPath());
            //     $imageData = base64_encode($imageData);
            //     $model->img = $imageData;
            // }


            $model->name = array_key_exists('name', $data) ? $data['name']  :  $model->name;
            $model->notes =  array_key_exists('notes', $data) ? $data['notes']  :  $model->notes;
            $model->price = array_key_exists('price', $data) ? $data['price']  :  $model->price;
            $model->sell = array_key_exists('sell', $data) ? $data['sell']  :  $model->sell;
            $model->invoice_id = array_key_exists('invoice_id', $data) ? $data['invoice_id']  :  $model->invoice_id;
            $model->code = array_key_exists('code', $data) ? $data['code']  :  $model->code;
            $res =  $model->save();
        };

        return response()->json($res);
    }

    public function delete($id)
    {

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'المنتج غير موجود'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'تم حذف المنتج بنجاح'], 200);
    }
}
