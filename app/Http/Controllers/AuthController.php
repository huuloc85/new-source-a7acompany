<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Helpers\LogHelper;
use App\Http\Requests\EmployeeChangeInfoRequest;
use App\Http\Requests\UserChangeInfoRequest;
use App\Http\Requests\UserChangePasswordRequest;
use App\Models\Celender;
use App\Models\CelenderDetailEatroom;
use App\Models\CelenderDetailWC;
use App\Models\CelenderDetailWCCleanMen;
use App\Models\CelenderDetailWCCleanWomen;
use App\Models\Employee;
use App\Models\LoginHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    // view login
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('admin.home');
        } else {
            return view('auth.login');
        }
    }

    // handle loginuse Carbon\Carbon;
    public function handleLogin(Request $request)
    {
        $credentials = $request->only('phone', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Kiểm tra nếu nhân viên đã nghỉ việc
            if ($user && $user->deleted_at !== null) {
                toast('Bạn đã nghỉ việc!', 'error', 'top-right');
                Auth::logout();

                return redirect()->route('login');
            }

            $employee = Employee::where('id', $user->id)->first();

            // Kiểm tra sinh nhật
            $today = now()->format('m-d');
            $birthdayEmployees = Employee::whereRaw("DATE_FORMAT(birthday, '%m-%d') = ?", [$today])
                ->whereNull('deleted_at')
                ->pluck('name');

            $isBirthday = $birthdayEmployees->isNotEmpty();

            // Kiểm tra xem đã có bản ghi đăng nhập trong ngày hay chưa
            $loginHistoryExists = LoginHistory::where('employee_id', $employee->id)
                ->whereDate('date', now()->toDateString())
                ->exists();

            if (! $loginHistoryExists && $isBirthday) {
                // Chỉ hiển thị modal sinh nhật nếu chưa có bản ghi đăng nhập trong ngày
                session()->flash('birthday_check', true);
                session()->flash('birthday_employees', $birthdayEmployees);
            }

            // Kiểm tra lịch làm việc
            $calendar = Celender::latest('id')->first();

            if ($calendar) {
                $celenderId = $calendar->id;
                $upcomingDuties = [];

                // Kiểm tra cho ngày hiện tại và 2 ngày tiếp theo
                for ($i = 0; $i < 3; $i++) {
                    $checkDate = now()->addDays($i);
                    $currentDay = 'day'.$checkDate->day; // day1, day2, ..., day31

                    // Danh sách các điều kiện lịch trực
                    $duties = [
                        'Trực phòng ăn' => CelenderDetailEatroom::class,
                        'Trực nhà vệ sinh nữ' => CelenderDetailWCCleanWomen::class,
                        'Trực nhà vệ sinh nam' => CelenderDetailWCCleanMen::class,
                    ];

                    // Kiểm tra từng loại lịch trực
                    foreach ($duties as $dutyType => $model) {
                        if (
                            $model::where('celender_id', $celenderId)
                                ->where('employee_id', $employee->id)
                                ->where($currentDay, 'x')
                                ->exists()
                        ) {
                            $upcomingDuties[] = [
                                'date' => $checkDate,
                                'type' => $dutyType,
                            ];
                            break;
                        }
                    }

                    // Kiểm tra thêm điều kiện cho lịch "Đổ rác" (chỉ áp dụng vào thứ Bảy)
                    // if (
                    //     $checkDate->isSaturday() &&
                    //     CelenderDetailWC::where('celender_id', $celenderId)
                    //     ->where('employee_id', $employee->id)
                    //     ->whereIn($currentDay, ['day1', 'day2', 'day3', 'day4', 'day5'])
                    //     ->exists()
                    // ) {
                    //     $upcomingDuties[] = [
                    //         'date' => $checkDate,
                    //         'type' => 'Đổ rác'
                    //     ];
                    // }
                }

                // Gán thông báo nếu tìm thấy lịch trực
                if (! empty($upcomingDuties)) {
                    session()->flash('cleaning_duties', $upcomingDuties);
                    session()->flash('has_duties', true);
                }
            }

            // Ghi log hoạt động đăng nhập
            $roleName = $employee->role_id == 15 || $employee->role_id == 16 || $employee->role_id == 17 ? 'Admin' : 'Nhân Viên';
            LogActivity::logViewActivity($user, "{$roleName} Đăng Nhập", "{$roleName} đã đăng nhập vào web");

            return redirect()->route('admin.home');
        }

        // Xử lý sai thông tin đăng nhập
        toast('Số điện thoại hoặc mật khẩu không đúng!', 'error', 'top-right');

        return redirect()->back();
    }

    // logout
    public function logout()
    {
        Auth::logout();
        toast('Bạn đã đăng xuất thành công!', 'success', 'top-right');

        return redirect()->route('login');
    }

    // profile
    public function profile()
    {
        return view('auth.profile');
    }

    // change profile for admin
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
            $newFileName = $fileName.'.'.$fileExtension; // Tên file mới
            // Lưu file vào thư mục storage/app/public/image với tên mới
            $request->file('photo')->storeAs('public/admin', $newFileName);
            // Gán trường image của đối tượng task với tên mới
            $employee->photo = $newFileName;
        }

        try {
            $employee->save();
            if ($request->hasFile('photo')) {
                $image = 'public/admin/'.$oldImg;
                Storage::delete($image);
            }
            toast('Cập nhật thông tin thành công!', 'success', 'top-right');

            return redirect()->back();
        } catch (\Exception $e) {
            LogHelper::saveLog('changePassword', $e->getMessage(), $e->getLine());
            toast('Cập nhật thông tin không thành công!', 'error', 'top-right');
            if ($request->hasFile('photo')) {
                $image = 'public/admin/'.$newFileName;
                Storage::delete($image);
            }

            return redirect()->back();
        }
    }

    // change info for employee
    public function changeInfo(EmployeeChangeInfoRequest $request)
    {
        $employee = Employee::find(Auth()->user()->id);
        $employee->name = trim($request->name);
        $employee->phone = trim($request->phone);
        $employee->id = trim($request->code);
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

    // change password
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

    // reset password
    public function resetPassword($id)
    {
        try {
            $employee = Employee::find($id);
            $code = $employee->id;
            $role = $employee->role->role_name;
            if ($role == 'admin') {
                $employee->password = bcrypt('admin');
            } elseif ($role == 'manager') {
                $employee->password = bcrypt('manager');
            } elseif ($role == 'accountant') {
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
