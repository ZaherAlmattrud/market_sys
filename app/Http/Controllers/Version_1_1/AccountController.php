<?php

namespace App\Http\Controllers\Version_1_1;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Area;
use App\Models\User;
use App\Models\UserType;
use App\Models\AccountDetail;
use App\Models\Sell;
use App\Models\SellDetail;
use App\Models\Invoice;
use App\Models\Arrested;
use App\Models\Paid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class AccountController extends Controller
{
    //



    public function clearAccount($accountId){


     

        $user = User::where('account_id' ,$accountId )->first();

        AccountDetail::where('account_id' ,$accountId )->delete();

        $sellInvoicesIds  = Sell::where('user_id' ,$user->id )->pluck('id')->toArray();

        SellDetail::whereIn('sell_id' , $sellInvoicesIds )->delete();

        Sell::where('user_id' ,$user->id )->delete();

        Invoice::where('account_id' ,$accountId )->update(['total'=>'0']);

        Arrested::where('account_id' ,$accountId )->delete();

        Paid::where('account_id' ,$accountId )->delete();

    }


    public function getAllAccountsCash(){


        

          
       
             

        $res =   User::orderBy('id', 'desc')->get();//Account::with(['user'])->orderBy('id', 'desc')->get();

        $response = [] ;
        $data =  $res->map(function ($item)use(&$response) {

        $dd = $this->getAccountSummaryTotal($item->account_id);

            $user = $item->user ;
            $area =  Area::where('id' ,$item->area_id )->first();

            if ( $dd['total'] > 0 ){

                if ( $item->user_type != 2 ){

                    $response[] = [
                        'id' => $item->id,
                        'area' =>  $area  ?  $area->name : 'دمشق' ,
                        'person_name' =>   $item->user_name  ,
                        'account_user_type' =>  $item->userType->type_name ,
                        'total' =>   $dd['total'],
                        'debts' =>  $dd['total']
        
                    ];

                }

              


            }
         
        });


        return response()->json($response);
    }

    public function get($id)
    {

        $data = [];
        return response()->json($data);
    }


    public function getAccountSummaryTotal($accountId){

        $id = 1 ;
        $total = 0 ;
        $data = [];
        $items = [];
        $user = User::where('account_id' ,$accountId )->first();
        Log::info("Account ID : ".$accountId);
        
        $userType = UserType::where('id' ,  $user->user_type)->first();
        $data['user_name'] =   $user->user_name ;

        $bookItemsTotal = 0 ;
        $bookItems = AccountDetail::where('account_id' ,$accountId )->get();

        $bookItems->map(function ( $item) use ( &$items , &$id , &$bookItemsTotal ){

            $qua = $item['quantity'] > 1 ? '  عدد '.$item['quantity'] : null ;
            $it['identity'] = $id ;
            $it['description'] = $item['description']. $qua ;
            $it['total'] = $item['total'] ;
            $it['date'] = $item['date'] ;
            $it['notes'] = $item['notes'] ;

           $items[] = $it;
            $id++ ;
            $bookItemsTotal = $bookItemsTotal + $item['total'] ;

        });

        $sellInvoicesTotal = 0 ;
        $sellInvoices  = Sell::where('user_id' ,$user->id )->get();

        $sellInvoices->map(function ( $item) use ( &$items , &$id , &$sellInvoicesTotal){

            $it['identity'] = $id ; 
            $it['description'] = 'فاتورة مبيعات رقم :  ' . $item['id'] ; ;
            $it['total'] = SellDetail::where('sell_id' , $item['id'] )->sum('total') ;
            $it['date'] = $item['date'] ;
            $it['notes'] = $item['notes'] ;


            // if ( ! ($item['is_paid'] == 'مدفوعة' || $item['is_paid'] == 'تسعير') ){


                $items[] = $it;
                $id++ ;
                $sellInvoicesTotal =  $sellInvoicesTotal +  $it['total'] ;

            // }
            

        

        });


        $purchoiceInvoicesTotal = 0 ;
        $purchoiceInvoices  = Invoice::where('account_id' ,$accountId )->get();

        $purchoiceInvoices->map(function ( $item) use ( &$items , &$id , &$purchoiceInvoicesTotal){

            $it['identity'] = $id ; 
            $it['description'] = '  فاتورة مشتريات رقم  :   ' . $item['id'] ;
            $it['total'] = $item['total']   ;
            $it['date'] = $item['date'] ;
            $it['notes'] = $item['notes'] ;

            if ( $item['total']  != 0  ){


                $items[] = $it;
                $id++ ;
                $purchoiceInvoicesTotal =   $purchoiceInvoicesTotal +  $it['total'] ;
    

            }

   
        });


        $arrestedsTotals = 0 ;
        $arresteds = Arrested::where('account_id' ,$accountId )->get();

        $arresteds->map(function ( $item) use ( &$items , &$id, &$arrestedsTotals){
            $it['identity'] = $id ; 
            $it['description'] = 'رصيد مقبوض' ;
            $it['total'] = $item['total']   ;
            $it['date'] = $item['date'] ;
            $it['notes'] = $item['notes'] ;

           $items[] = $it;
            $id++ ;
            $arrestedsTotals =  $arrestedsTotals +   $it['total'] ;

        });


        $paidsTotals = 0 ;
        $paids = Paid::where('account_id' ,$accountId )->get();

        $paids->map(function ( $item) use ( &$items , &$id, &$paidsTotals){

            $it['identity'] = $id ; 
            $it['description'] = 'رصيد مدفوع' ;
            $it['total'] = $item['total']   ;
            $it['date'] = $item['date'] ;
            $it['notes'] = $item['notes'] ;

           $items[] = $it;
            $id++ ;
            $paidsTotals =  $paidsTotals +   $it['total'] ;

        });


       

            $incomeTotal =   $arrestedsTotals +   $purchoiceInvoicesTotal  ;
            $outcomeTotal =   $paidsTotals +     $sellInvoicesTotal +  $bookItemsTotal ;

             if (  $userType ->type_name !== "تاجر"){

                $total =   $outcomeTotal -  $incomeTotal  ;

             }else{

                 $total =     $incomeTotal - $outcomeTotal   ;

                
             }
            

      

        $data['data'] =   $items ;
        $data['total'] =   $total ;

        return $data ;


    }

    public function getAccountSummary($accountId){


       
        $data = $this->getAccountSummaryTotal($accountId);
       
        return response()->json($data);

    }

    public function create(Request $request)
    {

        $data = [];
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
