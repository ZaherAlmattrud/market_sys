<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
      $query = Audit::with('user'); // 

        // 🔍 فلترة بالبحث
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('event', 'like', "%{$search}%")
                ->orWhere('auditable_type', 'like', "%{$search}%")
                  ->orWhere('user_id', $search);

                  
            });
        }

        // 📄 عدد النتائج لكل صفحة (افتراضي 10)
        $perPage = $request->input('per_page', 1);

        // 🔁 ترتيب من الأحدث
        $audits = $query->latest()->paginate($perPage);

        return response()->json($audits);
    }
}
