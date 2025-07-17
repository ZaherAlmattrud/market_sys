<?php

namespace App\Http\Controllers\Version_1_1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Version_1_1\AccountController;

class UsersController extends Controller
{
    //


    private $accountController ;

    function __construct(AccountController $accountController){

            $this->accountController = $accountController ;
    }


    public function getUserInfo ($id){


         $user = User::with(['userType', 'area'])->findOrFail($id);

    return response()->json([
        'id' => $user->id,
        'user_name' => $user->user_name,
        'mobile' => $user->mobile,
        'number_in_book' => $user->number_in_book,
        'userType' => [
            'type_name' => $user->userType?->type_name,
        ],
        'area' => [
            'name' => $user->area?->name,
        ],
        'avatar' => 'storage/uploads/profile.jpg', // صورة افتراضية
    ]);
    }


    public function getAllSystemUsers(Request $request){


    $search = $request->query('search');
    $pageSize = $request->query('pageSize', 6); // عدد النتائج في الصفحة (افتراضي 6)

    $query = User::with(['userType'])
        ->whereHas('userType', function ($q) {
            $q->whereNotIn('type_name', ['مورد', 'تاجر', 'زبون']);
       });

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('user_name', 'like', "%{$search}%");
        });
    }

    $users = $query->orderBy('id', 'desc')->paginate($pageSize);

    $transformed = $users->getCollection()->transform(function ($item) {
        return [
            'id' => $item->id,
            'user_name' => $item->user_name,
            'user_type' => $item->userType ? $item->userType->type_name : 'غير محدد',
            'area' => $item->area ? $item->area->name : 'غير محدد',
            'account' => $item->id,
            'number_in_book' => $item->number_in_book,
            'mobile' => $item->mobile,
            'balance' => null,
        ];
    });

    return response()->json([
        'data' => $transformed,
        'links' => [
            'current_page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
            'per_page' => $users->perPage(),
            'total' => $users->total(),
            'next_page_url' => $users->nextPageUrl(),
            'prev_page_url' => $users->previousPageUrl(),
        ]
    ]);


    }

   public function getAllUserWithPagination(Request $request)
{
    $search = $request->query('search');
    $pageSize = $request->query('pageSize', 6); // افتراضي 6 لو ما وصل شيء

    $query = User::with(['userType', 'area']);

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('user_name', 'like', "%{$search}%");
              
        });
    }

    $users = $query->orderBy('id', 'desc')->paginate($pageSize);

    $transformed = $users->getCollection()->transform(function ($item) {
        return [
            'id' => $item->id,
            'user_name' => $item->user_name,
            'user_type' => $item->userType ? $item->userType->type_name : 'غير محدد',
            'area' => $item->area ? $item->area->name : 'غير محدد',
            'account' => $item->id,
            'number_in_book' => $item->number_in_book,
            'mobile' => $item->mobile,
            'balance' => null,
        ];
    });

    return response()->json([
        'items' => $transformed,
        'total' => $users->total()
    ]);
}


    public function getAll()
    {

        
     
       
        $data = User::with(['area', 'userType'])->orderBy('id', 'desc')->get();

        $res = $data->map(function ($item) {

           
         //  $data = $this->accountController->getAccountSummaryTotal($item->account_id);
          
            return [
                'id' => $item->id,

                'user_name' => $item->user_name,

                'user_type' =>   $item->userType ?   $item->userType->type_name : 'غير محدد',

               'area' =>  $item->area  ? $item->area->name : 'غير محدد',

                'account' => $item->id,

                'number_in_book' => $item->number_in_book,

                'mobile' => $item->mobile,

                'balance' => NULL// $data['total'] ,  
            ];
            
        });




        return response()->json($res);
    }

    public function get($id)
    {

        $data = [];
        return response()->json($data);
    }

    public function create(Request $request)
    {

        $data = [];
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {

        $data =  $request->all();
        $model = User::where('id',$id)->first();
        $res = false ;
        if( $model  ){
            $model->user_name = array_key_exists('user_name' , $data) ? $data['user_name']  :  $model->user_name ; 
            $model->mobile = array_key_exists('mobile' , $data) ? $data['mobile']  :  $model->mobile ; 
          //  $model->user_type = array_key_exists('user_type' , $data) ? $data['user_type']  :  $model->user_type ; 
            $model->number_in_book = array_key_exists('number_in_book' , $data) ? $data['number_in_book']  :  $model->number_in_book ; 
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
