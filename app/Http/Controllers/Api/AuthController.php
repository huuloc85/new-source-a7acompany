<?php

namespace App\Http\Controllers\Api;

use App\Models\Celender;
use App\Models\CelenderDetailEatroom;
use App\Models\CelenderDetailWCCleanMen;
use App\Models\CelenderDetailWCCleanWomen;
use App\Models\Employee;
use App\Models\LoginHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    /* API */

    public function authLogin(Request $request)
    {
        $credentials = $request->only('phone', 'password');

        if (! Auth::attempt($credentials)) {
            return response()->json(['message' => 'Số điện thoại hoặc mật khẩu không đúng!'], 401);
        }

        $user = Auth::user();

        if ($user->deleted_at !== null) {
            return response()->json(['message' => 'Bạn đã nghỉ việc!'], 403);
        }

        $employee = Employee::find($user->id);
        $today = now()->format('m-d');
        $birthdayEmployees = Employee::whereRaw("DATE_FORMAT(birthday, '%m-%d') = ?", [$today])
            ->whereNull('deleted_at')
            ->pluck('name');
        $isBirthday = $birthdayEmployees->isNotEmpty();

        $loginHistoryExists = LoginHistory::where('employee_id', $employee->id)
            ->whereDate('date', now()->toDateString())
            ->exists();

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
                    if (
                        $model::where('celender_id', $calendar->id)
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

        // Xóa các token cũ của user này (optional)
        // $user->tokens()->delete();

        // Nhận giá trị expiresInMins từ request, mặc định 60 phút
        $expiresInMins = $request->input('expiresInMins', 60);
        $expiresAt = now()->addMinutes($expiresInMins);

        // Tạo token với thời gian hết hạn
        $tokenResult = $user->createToken('auth_token', ['*'], $expiresAt);

        // Lưu expires_at vào token
        $tokenResult->accessToken->expires_at = $expiresAt;
        $tokenResult->accessToken->save();

        return response()->json([
            'role_id' => $user->role_id,
            'role_name' => $user->role->role_name,
            'name' => $user->name,
            'image' => $user->photo,
            'is_birthday' => $isBirthday,
            'birthday_employees' => $birthdayEmployees,
            'cleaning_duties' => $upcomingDuties,
            'token' => $tokenResult->plainTextToken,
        ])->cookie('auth_token', $tokenResult->plainTextToken, $expiresInMins, null, null, false, true);

    }

    public function authLogout()
    {
        return response()->json(['message' => 'Đăng xuất thành công'])
            ->cookie('auth_token', null, -1, null, null, true, true);
    }

    public function authCheck()
    {
        $user = Auth::user();

        if ($user) {
            return response()->json([
                'role_id' => $user->role_id,
                'role_name' => $user->role->role_name,
                'name' => $user->name,
                'image' => $user->photo,
            ], 200);
        } else {
            return response()->json([
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized',
                ],
            ], 401);
        }
    }

    // Admin cập nhật thông tin của người khác
    public function authChangeProfile(Request $request, $id)
    {
        // $data = "Hello World";
        // return response()->json($data, 200);
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
    public function authChangeInfo(Request $request)
    {
        $user = Auth::user();

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return response()->json(['message' => 'Cập nhật thông tin cá nhân thành công!', 'user' => $user]);
    }

    public function authMe(Request $request)
    {
        return response()->json($request->user());
    }

    // Lấy thông tin profile của người dùng đang đăng nhập
    public function authProfile()
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => 'Người dùng chưa đăng nhập!'], 401);
        }

        return response()->json(['user' => $user]);
    }
}
