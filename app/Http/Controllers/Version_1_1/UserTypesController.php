<?php

namespace App\Http\Controllers\Version_1_1;

use App\Http\Controllers\Controller;
use App\Models\UserType;
use Illuminate\Http\Request;

class UserTypesController extends Controller
{
    // جلب كل أنواع المستخدمين
    public function getAll()
    {
        $data = UserType::all();
        return response()->json($data);
    }

    // جلب نوع مستخدم واحد حسب الـ ID
    public function get($id)
    {
        $userType = UserType::find($id);

        if (!$userType) {
            return response()->json(['message' => 'النوع غير موجود'], 404);
        }

        return response()->json($userType);
    }

    // إنشاء نوع مستخدم جديد
    public function create(Request $request)
    {
        $validated = $request->validate([
            'type_name' => 'required|string|max:255|unique:usertypes,type_name',
        ]);

        $userType = UserType::create($validated);

        return response()->json([
            'message' => 'تم إنشاء نوع المستخدم بنجاح',
            'data' => $userType,
        ], 201);
    }

    // تحديث نوع مستخدم
    public function update(Request $request, $id)
    {
        $userType = UserType::find($id);

        if (!$userType) {
            return response()->json(['message' => 'النوع غير موجود'], 404);
        }

        $validated = $request->validate([
            'type_name' => 'required|string|max:255|unique:usertypes,type_name,' . $id,
        ]);

        $userType->update($validated);

        return response()->json([
            'message' => 'تم التحديث بنجاح',
            'data' => $userType,
        ]);
    }

    // حذف نوع مستخدم
    public function delete($id)
    {
        $userType = UserType::find($id);

        if (!$userType) {
            return response()->json(['message' => 'النوع غير موجود'], 404);
        }

        // تحقق من وجود مستخدمين مرتبطين بهذا النوع
        if ($userType->users()->count() > 0) {
            return response()->json(['message' => 'لا يمكن حذف النوع لأنه مرتبط بمستخدمين'], 400);
        }

        $userType->delete();

        return response()->json(['message' => 'تم الحذف بنجاح']);
    }
}
