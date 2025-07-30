<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionController extends Controller
{
    //


    public function getPermissionsForRole($id)
    {

        $role =  Role::with('permissions')->find($id);

        if (!$role) {
            return response()->json(['message' => 'الدور غير موجود'], 404);
        }

        return response()->json($role->permissions);
    }

    public function getRole($id)
    {

        $role = Role::find($id);

        return response()->json($role);
    }

    // عرض جميع الأدوار
    public function roles()
    {
        $roles = Role::all();

        return response()->json($roles);
    }

    // عرض جميع الصلاحيات
    public function permissions()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    // إنشاء دور جديد
    public function createRole(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        Role::create(['name' => $request->name]);

        return redirect()->back()->with('success', 'Role created successfully');
    }

    // إنشاء صلاحية جديدة
    public function createPermission(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);

        return redirect()->back()->with('success', 'Permission created successfully');
    }

    // تعيين دور لمستخدم
    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($request->user_id);


        $user->assignRole($request->role);

        return redirect()->back()->with('success', 'Role assigned successfully');
    }

    // إزالة دور من مستخدم
    public function removeRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($request->user_id);

        // if (!auth()->user()->hasRole('admin')) {
        //     abort(403, 'Unauthorized');
        // }

        $user->removeRole($request->role);

        return redirect()->back()->with('success', 'Role removed successfully');
    }

    // تعيين صلاحية لمستخدم
    public function givePermission(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permission' => 'required|exists:permissions,name',
        ]);

        $user = User::findOrFail($request->user_id);

        // if (!auth()->user()->hasRole('admin')) {
        //     abort(403, 'Unauthorized');
        // }

        $user->givePermissionTo($request->permission);

        return redirect()->back()->with('success', 'Permission granted successfully');
    }

    // إزالة صلاحية من مستخدم
    public function removePermission(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permission' => 'required|exists:permissions,name',
        ]);

        $user = User::findOrFail($request->user_id);

        // if (!auth()->user()->hasRole('admin')) {
        //     abort(403, 'Unauthorized');
        // }

        $user->revokePermissionTo($request->permission);

        return redirect()->back()->with('success', 'Permission removed successfully');
    }

    public function deletePermission($permissionId)
    {

        $permission = Permission::find($permissionId);

        if (!$permission) {
            return response()->json(['message' => 'الصلاحية غير موجودة'], 404);
        }

        $permission->delete();
        return response()->json(['message' => 'تم حذف الصلاحية بنجاح'], 200);
    }

    public function deleteRole($roleId)
    {

        $role = Role::find($roleId);

        if (!$role) {
            return response()->json(['message' => 'الدور غير موجود'], 404);
        }

        $role->delete();

        return response()->json(['message' => 'تم حذف الدور بنجاح'], 200);
    }


    public function updateRolePermissions(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);

        $toAdd = $request->permissions_to_add ?? [];
        $toRemove = $request->permissions_to_remove ?? [];

        $role->givePermissionTo($toAdd);
        // $role->revokePermissionTo($toRemove);

        foreach ($toRemove as $permission) {
            $role->revokePermissionTo($permission);
        }

        return response()->json(['message' => 'تم التحديث بنجاح']);
    }
}
