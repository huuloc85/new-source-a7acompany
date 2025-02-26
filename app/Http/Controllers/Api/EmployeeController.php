<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    // Lấy danh sách nhân sự
    public function index(Request $request)
    {
        $employees = Employee::query()->whereNotIn('role_id', [15, 17])->whereNull('deleted_at');

        if (! empty($request->role_id)) {
            $employees->where('role_id', $request->role_id);
        }
        if (! empty($request->category_celender_id)) {
            $employees->where('category_celender_id', $request->category_celender_id);
        }
        if (! empty($request->address)) {
            $employees->where('address', 'LIKE', '%'.$request->address.'%');
        }
        if (! empty($request->home_town)) {
            $employees->where('home_town', 'LIKE', '%'.$request->home_town.'%');
        }
        if (! empty($request->phone)) {
            $employees->where('phone', 'LIKE', '%'.$request->phone.'%');
        }
        if (! empty($request->CCCD)) {
            $employees->where('CCCD', 'LIKE', '%'.$request->CCCD.'%');
        }
        if (! empty($request->code)) {
            $employees->where('code', 'LIKE', '%'.$request->code.'%');
        }
        if (! empty($request->name)) {
            $employees->where('name', 'LIKE', '%'.$request->name.'%');
        }
        if (! empty($request->company)) {
            $employees->where('company', 'LIKE', '%'.$request->company.'%');
        }

        $employees = $employees->orderBy('id', 'DESC')->paginate($request->limit ?? 10);

        return response()->json([
            'status' => 'true',
            'data' => $employees,
        ], 200);
    }

    public function store(EmployeeStoreRequest $request)
    {
        try {
            $employee = new Employee;
            $employee->name = trim($request->name);
            $employee->phone = trim($request->phone);
            $employee->code = trim($request->code);
            $employee->email = trim($request->email);
            $employee->birthday = trim($request->birthday);
            $employee->address = trim($request->address);
            $employee->home_town = trim($request->home_town);
            $employee->CCCD = trim($request->CCCD);
            $employee->role_id = trim($request->role_id);
            $employee->company = trim($request->company);
            $employee->category_celender_id = trim($request->category_celender_id);
            $employee->gender = trim($request->gender);
            $employee->marital_status = $request->marital_status;
            $employee->date_joining = trim($request->date_joining);
            $employee->password = bcrypt($request->code);

            // Xử lý ảnh đại diện
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $fileName = uniqid().'.'.$file->getClientOriginalExtension();
                $file->storeAs('public/employee', $fileName);
                $employee->photo = $fileName;
            }

            // Xử lý ảnh thẻ
            if ($request->hasFile('card_photo')) {
                $fileCard = $request->file('card_photo');
                $fileNameCard = uniqid().'.'.$fileCard->getClientOriginalExtension();
                $fileCard->storeAs('public/employee/card', $fileNameCard);
                $employee->card_photo = $fileNameCard;
            }

            $employee->save();

            return response()->json([
                'message' => 'Thêm nhân sự mới thành công!',
                'employee' => $employee,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['false' => 'Thêm nhân sự không thành công!'], 500);
        }
    }

    public function update(EmployeeUpdateRequest $request, $id)
    {
        try {
            $employee = Employee::findOrFail($id);

            $employee->name = trim($request->name);
            $employee->phone = trim($request->phone);
            $employee->email = trim($request->email);
            $employee->birthday = trim($request->birthday);
            $employee->address = trim($request->address);
            $employee->home_town = trim($request->home_town);
            $employee->role_id = trim($request->role_id);
            $employee->company = trim($request->company);
            $employee->category_celender_id = trim($request->category_celender_id);
            $employee->gender = trim($request->gender);
            $employee->marital_status = $request->marital_status;
            $employee->date_joining = trim($request->date_joining);

            // Cập nhật ảnh đại diện
            if ($request->hasFile('photo')) {
                if ($employee->photo) {
                    Storage::delete('public/employee/'.$employee->photo);
                }
                $file = $request->file('photo');
                $fileName = uniqid().'.'.$file->getClientOriginalExtension();
                $file->storeAs('public/employee', $fileName);
                $employee->photo = $fileName;
            }

            // Cập nhật ảnh thẻ
            if ($request->hasFile('card_photo')) {
                if ($employee->card_photo) {
                    Storage::delete('public/employee/card/'.$employee->card_photo);
                }
                $fileCard = $request->file('card_photo');
                $fileNameCard = uniqid().'.'.$fileCard->getClientOriginalExtension();
                $fileCard->storeAs('public/employee/card', $fileNameCard);
                $employee->card_photo = $fileNameCard;
            }

            $employee->save();

            return response()->json([
                'message' => 'Cập nhật nhân sự thành công!',
                'employee' => $employee,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['false' => 'Cập nhật nhân sự thất bại!'], 500);
        }
    }

    // Xóa nhân sự (Đưa vào thùng rác)
    public function delete($id)
    {
        $employee = Employee::find($id);

        if (! $employee) {
            return response()->json(['status' => 'false', 'message' => 'Nhân sự không tồn tại!'], 404);
        }

        try {
            $employee->deleted_at = Carbon::now();
            $employee->save();

            return response()->json(['status' => 'true', 'message' => 'Nhân sự đã được đưa vào thùng rác!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Lỗi khi xóa nhân sự!'], 500);
        }
    }

    // Lấy danh sách nhân sự trong thùng rác
    public function getTrash(Request $request)
    {
        $employees = Employee::whereNotNull('deleted_at')->orderBy('deleted_at', 'DESC')->paginate($request->limit ?? 10);

        return response()->json(['status' => 'true', 'data' => $employees], 200);
    }

    public function restore($id)
    {
        $employee = Employee::whereNotNull('deleted_at')->find($id);
        if (! $employee) {
            return response()->json(['status' => false, 'message' => 'Nhân viên không tồn tại trong thùng rác'], 404);
        }

        $employee->deleted_at = null;
        $employee->save();

        return response()->json(['status' => true, 'message' => 'Nhân viên đã được khôi phục']);
    }
}
