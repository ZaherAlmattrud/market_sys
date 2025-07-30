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

  function getLastFive(Request $request)
  {

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
