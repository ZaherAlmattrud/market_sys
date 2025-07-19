<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use OwenIt\Auditing\Models\Audit;

class ActivityLogController extends Controller
{
    //


    function getLastFiveActivityLog(Request $request)
    {


        // $audits = Audit::orderBy('created_at', 'desc')
        //     ->limit(5)
        //     ->get();

        // return response()->json($audits);
 
 

$audits = Audit::orderBy('created_at', 'desc')
    ->limit(3)
    ->get()
    ->map(function ($audit) {
        $user = null;

        // إذا فيه user_type و user_id نجيب المستخدم
        if ($audit->user_type && $audit->user_id) {
            $userClass = $audit->user_type;
            $user = $userClass::find($audit->user_id);
        }

        // نضيف اسم المستخدم (أو null إذا ما فيه)
        $audit->user_name = $user ? ($user->user_name ?? $user->user_name ?? 'اسم غير معروف') : null;

           return $audit->makeHidden('user_type');

        return $audit;
    });

return response()->json($audits);

        
    }
}
