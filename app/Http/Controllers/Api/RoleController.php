<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // Danh sách vai trò
    public function index(Request $request)
    {
        $roles = Role::search()
            ->whereNotIn('id', [15, 16, 17])
            ->orderBy('id', 'DESC')
            ->paginate(Role::paginate);

        return response()->json([
            'success' => true,
            'data' => $roles,
            'total' => $roles->total(),
        ]);
    }

    // Thêm vai trò
    public function store(Request $request)
    {
        try {
            $role = Role::create(['role_name' => trim($request->role_name)]);

            return response()->json(['success' => true, 'message' => 'Thêm chức vụ mới thành công!', 'data' => $role]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Thêm chức vụ mới không thành công!']);
        }
    }

    // Cập nhật vai trò
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if (! $role || in_array($role->role_name, ['admin', 'manager', 'accountant'])) {
            return response()->json(['success' => false, 'message' => 'Không thể cập nhật chức vụ này!']);
        }

        try {
            $role->update(['role_name' => trim($request->role_name)]);

            return response()->json(['success' => true, 'message' => 'Cập nhật chức vụ thành công!', 'data' => $role]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Cập nhật chức vụ không thành công!']);
        }
    }

    // Xóa vai trò
    public function delete($id)
    {
        $role = Role::find($id);
        if (! $role || in_array($role->role_name, ['admin', 'manager', 'accountant'])) {
            return response()->json(['success' => false, 'message' => 'Không thể xóa chức vụ này!']);
        }

        if (Employee::where('role_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Có nhân viên thuộc chức vụ này!']);
        }

        try {
            $role->delete();

            return response()->json(['success' => true, 'message' => 'Xóa chức vụ thành công!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Xóa chức vụ không thành công!']);
        }
    }
}
