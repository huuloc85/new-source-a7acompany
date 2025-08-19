<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\SidebarItem;
use Illuminate\Http\Request;

class RBACController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::all();
        $permissions = Permission::all();

        $selectedRoleIds = (array) $request->input('role_ids', []);

        if (empty($selectedRoleIds)) {
            $superAdmin = Role::with('permissions')->where('role_name', 'Super Admin')->first();

            // Nếu Super Admin tồn tại và KHÔNG có permissions thì mới chọn mặc định
            if ($superAdmin && $superAdmin->permissions->isEmpty()) {
                $selectedRoleIds = [$superAdmin->id];
            }
        }

        $selectedRoles = Role::with('permissions')->whereIn('id', $selectedRoleIds)->get();

        $allRolePermissions = $selectedRoles->mapWithKeys(function ($role) {
            return [$role->id => $role->permissions->pluck('id')->toArray()];
        });

        // Lấy danh sách permission cho modal Sidebar
        $permissionsSidebar = Permission::whereIn('display_area', ['sidebar', 'both'])
            ->get(['id', 'name', 'key', 'type', 'display_area']);

        // Lấy các sidebar item có permission_id thuộc các permission sẽ render, rồi group theo permission_id
        $sidebarItemsByPermission = SidebarItem::whereIn('permission_id', $permissionsSidebar->pluck('id'))
            ->orderBy('id') // hoặc sort_order nếu có
            ->get(['id', 'title', 'key', 'permission_id'])
            ->groupBy('permission_id');

        return view('rbac.index', compact(
            'roles',
            'permissions',
            'selectedRoles',
            'selectedRoleIds',
            'allRolePermissions',
            'permissionsSidebar',
            'sidebarItemsByPermission'
        ));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'exists:roles,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissions = $validated['permissions'] ?? [];
        $updatedRoles = Role::whereIn('id', $validated['role_ids'])->get()->each(function ($role) use ($permissions) {
            $role->permissions()->sync($permissions);
        })->pluck('role_name')->toArray();

        return redirect()->route('rbac.index', ['role_ids' => $validated['role_ids']])
            ->with('success', 'Phân quyền đã được cập nhật cho '.count($updatedRoles).' vai trò: '.implode(', ', $updatedRoles));
    }
}
