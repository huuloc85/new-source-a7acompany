<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RBACController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $selectedRoleIds = (array) $request->role_ids;
        $selectedRoles = Role::whereIn('id', $selectedRoleIds)->get();

        $allRolePermissions = $selectedRoles->mapWithKeys(function ($role) {
            return [$role->id => $role->permissions->pluck('id')->toArray()];
        });

        return view('rbac.index', compact(
            'roles',
            'permissions',
            'selectedRoles',
            'selectedRoleIds',
            'allRolePermissions'
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

    public function getRolePermissions($roleId)
    {
        $role = Role::findOrFail($roleId);

        return response()->json([
            'role' => $role,
            'permissions' => $role->permissions->pluck('id'),
        ]);
    }

    public function compareRoles(Request $request)
    {
        $validated = $request->validate([
            'role_ids' => 'required|array|min:2',
            'role_ids.*' => 'exists:roles,id',
        ]);

        $roles = Role::whereIn('id', $validated['role_ids'])->with('permissions')->get();
        $comparison = $roles->mapWithKeys(fn ($role) => [
            $role->id => [
                'name' => $role->role_name,
                'permissions' => $role->permissions->pluck('id')->toArray(),
            ],
        ]);

        $common = array_reduce($comparison->pluck('permissions')->toArray(), 'array_intersect', array_shift($comparison->pluck('permissions')->toArray()) ?? []);
        $unique = $comparison->map(fn ($r) => array_diff($r['permissions'], $common));

        return response()->json([
            'roles' => $comparison,
            'common_permissions' => $common,
            'unique_permissions' => $unique,
            'all_permissions' => Permission::all(),
        ]);
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'exists:roles,id',
            'action' => 'required|in:add,remove,replace',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $roles = Role::whereIn('id', $validated['role_ids'])->get();
        $permissions = $validated['permissions'];
        $action = $validated['action'];

        foreach ($roles as $role) {
            $current = $role->permissions->pluck('id')->toArray();

            $new = match ($action) {
                'add' => array_unique(array_merge($current, $permissions)),
                'remove' => array_diff($current, $permissions),
                default => $permissions,
            };

            $role->permissions()->sync($new);
        }

        $actionText = ['add' => 'thêm quyền cho', 'remove' => 'xóa quyền khỏi', 'replace' => 'cập nhật quyền cho'];

        return redirect()->route('rbac.index', ['role_ids' => $validated['role_ids']])
            ->with('success', 'Đã '.$actionText[$action].' '.$roles->count().' vai trò: '.$roles->pluck('role_name')->implode(', '));
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'exists:roles,id',
            'format' => 'in:json,csv',
        ]);

        $roles = Role::whereIn('id', $validated['role_ids'])->with('permissions')->get();
        $data = $roles->map(fn ($role) => [
            'role_id' => $role->id,
            'role_name' => $role->role_name,
            'permissions' => $role->permissions->pluck('name')->toArray(),
            'permission_keys' => $role->permissions->pluck('key')->toArray(),
        ]);

        if ($validated['format'] === 'csv') {
            $filename = 'role_permissions_'.now()->format('Y-m-d_H-i-s').'.csv';

            return response()->stream(function () use ($data) {
                $f = fopen('php://output', 'w');
                fputcsv($f, ['Role ID', 'Role Name', 'Permissions', 'Permission Keys']);
                foreach ($data as $row) {
                    fputcsv($f, [
                        $row['role_id'],
                        $row['role_name'],
                        implode('; ', $row['permissions']),
                        implode('; ', $row['permission_keys']),
                    ]);
                }
                fclose($f);
            }, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ]);
        }

        return response()->json($data, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
