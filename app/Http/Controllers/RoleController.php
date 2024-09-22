<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Employee;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // view list
    public function index(Request $request)
    {
        $roles = Role::search();
        $totalRole = $roles->get();
        $roles = $roles->where('id', '!=', 15)
            ->where('id', '!=', 17)
            ->where('id', '!=', 16);
        $totalRole = $roles->get();
        $roles = $roles->orderBy('id', 'DESC')->paginate(Role::paginate);
        $total = count($totalRole);

        return view('role.index', compact('roles', 'total'));
    }

    //view add
    public function add()
    {
        return view('role.add');
    }

    //store new role
    public function store(RoleStoreRequest $request)
    {
        $role = new Role();
        $role->role_name = trim($request->role_name);

        try {
            $role->save();
            toast('Thêm chức vụ mới thành công!', 'success', 'top-right');
            return redirect()->route('admin.role.home');
        } catch (\Exception $th) {
            toast('Thêm chức vụ mới không thành công!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    //view edit
    public function edit($id)
    {
        $role = Role::find($id);
        if ($role->role_name == 'admin' || $role->role_name == 'manager' || $role->role_name == 'accountant') {
            toast('Không thể cập nhật thông tin chức vụ này!', 'success', 'top-right');
            return redirect()->route('admin.role.home');
        }
        return view('role.edit', compact('role'));
    }

    //update role
    public function update(RoleUpdateRequest $request, $id)
    {
        $role = Role::find($id);
        if ($role->role_name == 'admin' || $role->role_name == 'manager' || $role->role_name == 'accountant') {
            toast('Không thể cập nhật thông tin chức vụ này!', 'success', 'top-right');
            return redirect()->route('admin.role.home');
        }
        $role->role_name = trim($request->role_name);

        try {
            $role->save();
            toast('Cập nhật chức vụ thành công!', 'success', 'top-right');
            return redirect()->route('admin.role.home');
        } catch (\Exception $th) {
            toast('Cập nhật chức vụ không thành công!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    //delete role
    public function delete($id)
    {
        $role = Role::findOrFail($id);
        try {
            if ($role->role_name == 'admin' || $role->role_name == 'manager' || $role->role_name == 'accountant') {
                toast('Không thể xóa chức vụ này!', 'success', 'top-right');
                return redirect()->route('admin.role.home');
            }
            $employee = Employee::where('role_id', $id)->first();
            if ($employee) {
                toast('Có nhân viên thuộc chức vụ này!', 'error', 'top-right');
                return redirect()->route('admin.role.home');
            }
            $role->delete();
            toast('Xóa chức vụ thành công!', 'success', 'top-right');
            return redirect()->route('admin.role.home');
        } catch (\Exception $th) {
            toast('Xóa chức vụ không thành công!', 'error', 'top-right');
            return redirect()->route('admin.role.home');
        }
    }
}
