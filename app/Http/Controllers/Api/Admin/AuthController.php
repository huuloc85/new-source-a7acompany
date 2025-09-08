<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\HandleError;
use App\Helpers\LogActivity;
use App\Models\Celender;
use App\Models\CelenderDetailEatroom;
use App\Models\CelenderDetailWCCleanMen;
use App\Models\CelenderDetailWCCleanWomen;
use App\Models\Employee;
use App\Models\LoginHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

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
        $permissions = Auth::user()
            ->role
            ->permissions()
            ->select(['permissions.id', 'permissions.key', 'permissions.name', 'permissions.type', 'permissions.display_area'])
            ->with([
                'sidebarItems' => function ($q) {
                    $q->select(['id', 'permission_id', 'key', 'title', 'icon', 'path']);
                },
            ])
            ->get();
        $roleName = in_array($employee->role_id, [15, 21, 22]) ? 'Admin' : 'Nhân Viên';
        LogActivity::logViewActivity($user, "{$roleName} Đăng Nhập", "{$roleName} đã đăng nhập vào web");

        return response()->json([
            'id' => $user->id,
            'role_id' => $user->role_id,
            'role_name' => $user->role->role_name,
            'name' => $user->name,
            'image' => $user->photo,
            'is_birthday' => $isBirthday,
            'birthday_employees' => $birthdayEmployees,
            'cleaning_duties' => $upcomingDuties,
            'token' => $tokenResult->plainTextToken,
            'permissions' => $permissions,
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

    public function authProfile()
    {
        $id = auth()->user()->id;
        try {
            $employee = Employee::query()
                ->select(
                    'id',
                    'name',
                    'phone',
                    'email',
                    'address',
                    'home_town',
                    'gender',
                    'birthday',
                    'CCCD',
                    'photo',
                    'card_photo',
                    'marital_status',
                    'date_joining',
                    'company',
                    'role_id',
                    'calendar_category_id',
                    'created_at',
                    'updated_at',
                )
                ->with([
                    'role:id,role_name',
                    'calendarCategory:id,name',
                ])
                ->where('id', $id)
                ->firstOrFail();

            return response()->json($employee, 200);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    public function authChangeProfile(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = auth()->user()->id;
            $employee = Employee::query()
                ->findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'phone' => 'sometimes|string|regex:/^0[0-9]{9}$/|unique:employees,phone,'.$id,
                'email' => 'nullable|email|unique:employees,email,'.$id,
                'CCCD' => 'sometimes|string|regex:/^[0-9]+$/|unique:employees,cccd,'.$id,
                'address' => 'sometimes|string',
                'home_town' => 'sometimes|string',
                'birthday' => 'sometimes|date|before:today|after:1900-01-01',
                'gender' => 'sometimes|in:male,female,other',
                'marital_status' => 'sometimes|in:single,married,divorced,widowed',
            ]);

            $employee->update($validated);

            DB::commit();

            return response()->json([
                'message' => 'Employee updated successfully!',
                'data' => $employee,
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();

            return HandleError::handle($e);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $validate = $request->validate([
                'password' => 'required|string',
                'newPassword' => 'required|string|min:6',
                'confirmPassword' => 'required|string|min:6',
            ]);
            $user = Auth::user();
            $employee = Employee::find($user->id);

            if (! $employee) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $current = $validate['password'];
            $new = $validate['newPassword'];
            $confirm = $validate['confirmPassword'];

            if (! Hash::check($current, $employee->password)) {
                return response()->json(['message' => 'Mật khẩu hiện tại không đúng'], 422);
            }

            if ($new !== $confirm) {
                return response()->json(['message' => 'Xác nhận mật khẩu không khớp'], 422);
            }

            // Cập nhật mật khẩu
            $employee->password = bcrypt($new);
            $employee->save();

            return response()->json(['message' => 'Thay đổi mật khẩu thành công'], 200);
        } catch (Throwable $e) {
            // LogHelper::saveLog('changePassword', $e->getMessage(), $e->getLine());
            return response()->json(['message' => 'Thay đổi mật khẩu thất bại'], 500);
        }
    }

    public function resetPassword($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $employee->password = bcrypt($id);
            $employee->save();

            return response()->json([
                'message' => 'Khôi phục mật khẩu thành công',
            ], 200);
        } catch (Throwable $e) {
            // LogHelper::saveLog('resetPassword', $e->getMessage(), $e->getLine());
            return response()->json(['message' => 'Khôi phục mật khẩu thất bại'], 500);
        }
    }

    public function getBirthdayEmployees()
    {
        $today = Carbon::today();
        $employees = Employee::whereMonth('birthday', $today->month)
            ->whereDay('birthday', $today->day)
            ->get();

        return response()->json($employees);
    }
}
