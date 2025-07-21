<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();

        return view('permission.index', compact('permissions'));
    }

    public function create()
    {
        return view('permission.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|unique:permissions,key',
            'name' => 'required',
        ]);

        Permission::create($request->only('key', 'name'));

        return redirect()->route('permissions.index')->with('success', 'Tạo quyền thành công');
    }

    public function edit(Permission $permission)
    {
        return view('permission.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'key' => 'required|unique:permissions,key,'.$permission->id,
            'name' => 'required',
        ]);

        $permission->update($request->only('key', 'name'));

        return redirect()->route('permissions.index')->with('success', 'Cập nhật quyền thành công');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('permissions.index')->with('success', 'Xóa quyền thành công');
    }
}
