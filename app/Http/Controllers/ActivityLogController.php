<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use OwenIt\Auditing\Models\Audit;

class ActivityLogController extends Controller
{
    //


    function getLastFiveActivityLog(Request $request)
    {


        $audits = Audit::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json($audits);
    }
}
