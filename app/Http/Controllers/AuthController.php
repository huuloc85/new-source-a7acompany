<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Helpers\LogHelper;
use App\Http\Requests\UserChangeInfoRequest;
use App\Http\Requests\UserChangePasswordRequest;
use App\Http\Requests\EmployeeChangeInfoRequest;
use App\Models\Employee;
use App\Models\LoginHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Month;

class AuthController extends Controller
{
    //view login
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('admin.home');
        } else {
            return view('auth.login');
        }
    }

    //handle loginuse Carbon\Carbon;
    public function handleLogin(Request $request)
    {
        $credentials = $request->only('phone', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user && $user->deleted_at !== null) {
                toast('Bạn đã nghỉ việc!', 'error', 'top-right');
                Auth::logout();
                return redirect()->route('login');
            }

            toast('Bạn đã đăng nhập thành công!', 'success', 'top-right');
            $employee = Employee::where('id', $user->id)->first();
            if ($employee && ($employee->role_id == 15 || $employee->role_id == 16 || $employee->role_id == 17)) {
                LogActivity::logViewActivity($user, 'Admin Đăng Nhập', 'Admin đã đăng nhập vào web');
            } else {
                LogActivity::logViewActivity($user, 'Nhân Viên Đăng Nhập', 'Nhân viên đã đăng nhập vào web');
            }

            return redirect()->route('admin.home');
        }

        toast('Số điện thoại hoặc mật khẩu không đúng!', 'error', 'top-right');
        return redirect()->back();
    }



    //logout
    public function logout()
    {
        Auth::logout();
        toast('Bạn đã đăng xuất thành công!', 'success', 'top-right');
        return redirect()->route('login');
    }

    //profile
    public function profile()
    {
        return view('auth.profile');
    }

    //change profile for admin
    public function changeProfile(UserChangeInfoRequest $request)
    {
        $employee = Employee::find(Auth()->user()->id);
        // $employee->name = trim($request->name);
        // $employee->phone = trim($request->phone);
        $oldImg = $employee->photo;
        $file = $request->photo;
        if ($request->hasFile('photo')) {
            $fileExtension = $file->getClientOriginalName();
            $fileName = time(); // Tạo tên file dựa trên thời gian
            $newFileName = $fileName . '.' . $fileExtension; // Tên file mới
            //Lưu file vào thư mục storage/app/public/image với tên mới
            $request->file('photo')->storeAs('public/admin', $newFileName);
            // Gán trường image của đối tượng task với tên mới
            $employee->photo = $newFileName;
        }

        try {
            $employee->save();
            if ($request->hasFile('photo')) {
                $image = 'public/admin/' . $oldImg;
                Storage::delete($image);
            }
            toast('Cập nhật thông tin thành công!', 'success', 'top-right');
            return redirect()->back();
        } catch (\Exception $e) {
            LogHelper::saveLog('changePassword', $e->getMessage(), $e->getLine());
            toast('Cập nhật thông tin không thành công!', 'error', 'top-right');
            if ($request->hasFile('photo')) {
                $image = 'public/admin/' . $newFileName;
                Storage::delete($image);
            }
            return redirect()->back();
        }
    }

    //change info for employee
    public function changeInfo(EmployeeChangeInfoRequest $request)
    {
        $employee = Employee::find(Auth()->user()->id);
        $employee->name = trim($request->name);
        $employee->phone = trim($request->phone);
        $employee->code = trim($request->code);
        $employee->email = trim($request->email);
        $employee->birthday = trim($request->birthday);
        $employee->address = trim($request->address);
        $employee->home_town = trim($request->home_town);
        $employee->CCCD = trim($request->CCCD);
        $employee->gender = trim($request->gender);
        $employee->marital_status = trim($request->marital_status);
        $employee->date_joining = trim($request->date_joining);
        try {
            $employee->save();
            toast('Cập nhật thông tin thành công!', 'success', 'top-right');
            return redirect()->back();
        } catch (\Exception $e) {
            LogHelper::saveLog('changePassword', $e->getMessage(), $e->getLine());
            toast('Cập nhật thông tin không thành công!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    //change password
    public function changePassword(UserChangePasswordRequest $request)
    {
        try {
            $employee = Employee::find(Auth()->user()->id);
            $password = trim($request->password);
            $newpassword = trim($request->newpassword);
            $renewpassword = trim($request->renewpassword);

            if ($newpassword == $renewpassword) {
                if ((Hash::check($password, $employee->password))) {
                    $employee->password = bcrypt($newpassword);
                    $employee->save();
                    toast('Thay đổi mật khẩu thành công!', 'success', 'top-right');
                    return redirect()->back();
                } else {
                    toast('Mật khẩu hiện tại không đúng!', 'error', 'top-right');
                    return redirect()->back();
                }
            } else {
                toast('Mật khẩu nhập lại không khớp!', 'error', 'top-right');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            LogHelper::saveLog('changePassword', $e->getMessage(), $e->getLine());
            toast('Thay đổi mật khẩu thất bại!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    //reset password
    public function resetPassword($id)
    {
        try {
            $employee = Employee::find($id);
            $code = $employee->code;
            $role = $employee->role->role_name;
            if ($role == 'admin') {
                $employee->password = bcrypt('admin');
            } else if ($role == 'manager') {
                $employee->password = bcrypt('manager');
            } else if ($role == 'accountant') {
                $employee->password = bcrypt('accountant');
            } else {
                $employee->password = bcrypt($code);
            }
            $employee->save();
            toast('Khôi phục mật khẩu thành công!', 'success', 'top-right');
            return redirect()->back();
        } catch (\Exception $e) {
            LogHelper::saveLog('resetPassword', $e->getMessage(), $e->getLine());
            toast('Khôi phục mật khẩu thất bại!', 'error', 'top-right');
            return redirect()->back();
        }
    }
}
