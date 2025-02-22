<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Celender;
use App\Models\CelenderDetailEatroom;
use App\Models\CelenderDetailWCCleanMen;
use App\Models\CelenderDetailWCCleanWomen;
use App\Models\Employee;
use App\Models\LoginHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('phone', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Kiểm tra nhân viên đã nghỉ việc
            if ($user->deleted_at !== null) {
                return response()->json(['message' => 'Bạn đã nghỉ việc!'], 403);
            }

            // Lấy thông tin nhân viên
            $employee = Employee::find($user->id);

            // Kiểm tra sinh nhật hôm nay
            $today = now()->format('m-d');
            $birthdayEmployees = Employee::whereRaw("DATE_FORMAT(birthday, '%m-%d') = ?", [$today])
                ->whereNull('deleted_at')
                ->pluck('name');

            $isBirthday = $birthdayEmployees->isNotEmpty();

            // Kiểm tra xem đã đăng nhập trong ngày chưa
            $loginHistoryExists = LoginHistory::where('employee_id', $employee->id)
                ->whereDate('date', now()->toDateString())
                ->exists();

            // Kiểm tra lịch làm việc
            $upcomingDuties = [];
            $calendar = Celender::latest('id')->first();
            if ($calendar) {
                for ($i = 0; $i < 3; $i++) {
                    $checkDate = now()->addDays($i);
                    $currentDay = 'day'.$checkDate->day;

                    $duties = [
                        'Trực phòng ăn' => CelenderDetailEatroom::class,
                        'Trực nhà vệ sinh nữ' => CelenderDetailWCCleanWomen::class,
                        'Trực nhà vệ sinh nam' => CelenderDetailWCCleanMen::class,
                    ];

                    foreach ($duties as $dutyType => $model) {
                        if ($model::where('celender_id', $calendar->id)
                            ->where('employee_id', $employee->id)
                            ->where($currentDay, 'x')
                            ->exists()
                        ) {
                            $upcomingDuties[] = [
                                'date' => $checkDate->format('Y-m-d'),
                                'type' => $dutyType,
                            ];
                            break;
                        }
                    }
                }
            }

            // Tạo token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
                'is_birthday' => $isBirthday,
                'birthday_employees' => $birthdayEmployees,
                'cleaning_duties' => $upcomingDuties,
            ]);
        }

        return response()->json(['message' => 'Số điện thoại hoặc mật khẩu không đúng!'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Đăng xuất thành công']);
    }

    // Admin cập nhật thông tin của người khác
    public function changeProfile(Request $request, $id)
    {
        $admin = Auth::user(); // Lấy thông tin người dùng đang đăng nhập

        // Kiểm tra quyền admin
        if (! $admin || $admin->role_id != 15) { // Chỉ cho phép admin (role_id = 1)
            return response()->json(['message' => 'Bạn không có quyền thực hiện thao tác này!'], 403);
        }

        // Tìm nhân viên cần cập nhật
        $user = Employee::find($id);
        if (! $user) {
            return response()->json(['message' => 'Người dùng không tồn tại!'], 404);
        }

        // Validate dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'email' => 'required|email|unique:employees,email,'.$user->id, // Đúng bảng `employees`
            'role' => 'required|string',
        ]);

        // Cập nhật thông tin
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return response()->json(['message' => 'Cập nhật thông tin thành công!', 'user' => $user]);
    }

    // Nhân viên cập nhật thông tin cá nhân của chính họ
    public function changeInfo(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return response()->json(['message' => 'Cập nhật thông tin cá nhân thành công!', 'user' => $user]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    // Lấy thông tin profile của người dùng đang đăng nhập
    public function profile()
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => 'Người dùng chưa đăng nhập!'], 401);
        }

        return response()->json(['user' => $user]);
    }
}
