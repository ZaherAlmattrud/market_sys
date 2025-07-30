<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Log;

class AuthController extends Controller
{
    // 🟢 تسجيل مستخدم جديد
    public function register(Request $request)
    {


        Log::info($request->all());
        $request->validate([
            'user_name'        => 'required|string|max:255',
            'user_type'        => 'required|integer',
            'area_id'          => 'required|integer',
            'account_id'       => 'nullable|integer',
            'number_in_book'   => 'nullable|string|max:255',
            'mobile'            => 'required|string|unique:users,mobile',
            'password'         => 'required|string|confirmed|min:8',
        ]);

        $user = User::create([
            'user_name'      => $request->user_name,
            'user_type'      => $request->user_type,
            'area_id'        => $request->area_id,
            'account_id'     => $request->account_id,
            'number_in_book' => $request->number_in_book,
            'mobile'          => $request->mobile,
            'password'       => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ], 201);
    }

    // 🟢 تسجيل الدخول
    public function login(Request $request)
    {


    
        $request->validate([
            'user_name'    => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('user_name', $request->user_name)->first();


      

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'mobile' => ['البيانات غير صحيحة'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

           // جلب الأدوار والصلاحيات
    $roles = $user->roles->pluck('name'); // مجموعة أسماء الأدوار فقط
    $permissions = $user->getAllPermissions()->pluck('name'); // مجموعة أسماء الصلاحيات فقط


        return response()->json([
            'user'  => $user,
            'token' => $token,
               'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    // 🔴 تسجيل الخروج من الجلسة الحالية
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'تم تسجيل الخروج بنجاح.']);
    }

    // 🔴 تسجيل الخروج من جميع الأجهزة
    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'تم تسجيل الخروج من كل الجلسات.']);
    }

    // 🟢 عرض بيانات المستخدم الحالي
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    // 🟡 تحديث كلمة المرور
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|confirmed|min:8',
        ]);

        $user = $request->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'كلمة المرور الحالية غير صحيحة.'], 403);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json(['message' => 'تم تحديث كلمة المرور.']);
    }
}
