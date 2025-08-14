<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\HandleError;
use App\Helpers\LogActivity;
use App\Helpers\LogHelper;
use App\Helpers\NumberToWordsHelper;
use App\Helpers\UploadHelper;
use App\Models\Celender;
use App\Models\CelenderDetailEatroom;
use App\Models\CelenderDetailHNHC;
use App\Models\CelenderDetailWC;
use App\Models\CelenderDetailWCCleanMen;
use App\Models\CelenderDetailWCCleanWomen;
use App\Models\Employee;
use App\Models\SalaryManager;
use App\Models\SalaryOfficialA7A;
use App\Models\SalaryOfficialVVP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EmployeeController extends BaseController
{
    // Lấy danh sách nhân sự
    public function getEmployees(Request $request)
    {
        try {
            $employees = QueryBuilder::for(Employee::class)
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
                ->allowedFields([
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
                    'schedules.id',
                    'schedules.title',
                    'schedules.date',
                    'scheduleDetails.date',
                    'scheduleDetails.employee_id',
                    'scheduleDetails.schedule_id',
                    'scheduleDetails.is_wc_clean_men',
                    'scheduleDetails.is_wc_clean_women',
                    'scheduleDetails.is_wc_trash',
                    'scheduleDetails.is_eat_room',
                    'scheduleDetails.hnhc',
                ])
                ->allowedFilters([
                    'id',
                    'name',
                    'phone',
                    'email',
                    'address',
                    'home_town',
                    'gender',
                    'birthday',
                    'CCCD',
                    'marital_status',
                    'date_joining',
                    'company',
                    'role_id',
                    'calendar_category_id',
                    'schedules.title',
                    'schedules.date',
                    AllowedFilter::scope('schedules.date_between'),
                    'scheduleDetails.date',
                    'scheduleDetails.schedule_id',
                    'scheduleDetails.is_wc_clean_men',
                    'scheduleDetails.is_wc_clean_women',
                    'scheduleDetails.is_wc_trash',
                    'scheduleDetails.is_eat_room',
                    'scheduleDetails.hnhc',
                    AllowedFilter::scope('scheduleDetails.date_between'),
                ])
                ->defaultSort('-created_at')
                ->allowedSorts([
                    'name',
                    'phone',
                    'email',
                    'address',
                    'home_town',
                    'birthday',
                    'CCCD',
                    'date_joining',
                    'created_at',
                    'updated_at',
                ])
                ->allowedIncludes(['role', 'calendarCategory', 'schedules', 'scheduleDetails', 'attendanceRecords'])
                ->whereNotIn('role_id', [15, 17]);

            $limit = $request->limit;
            if (! is_null($limit) && $limit == 0) {
                $limit = $employees->count();
            }
            $employees = $employees->paginate($limit ?? 10);

            return response()->json($employees);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    public function getEmployee($id)
    {
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
                ->whereNotIn('role_id', [15, 17])
                ->firstOrFail();

            return response()->json($employee, 200);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    public function addEmployee(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'id' => 'required|string|unique:employees,id',
                'name' => 'required|string|max:255',
                'phone' => 'required|string|regex:/^0[0-9]{9}$/|unique:employees,phone',
                'email' => 'nullable|email|unique:employees,email',
                'CCCD' => 'required|string|regex:/^[0-9]+$/|unique:employees,cccd',
                'address' => 'required|string',
                'home_town' => 'required|string',
                'birthday' => 'required|date|before:today|after:1900-01-01',
                'gender' => 'required|in:male,female,other',
                'marital_status' => 'required|in:single,married,divorced,widowed',
                'company' => 'required|in:vvp,a7a',
                'date_joining' => 'required|date|after:2000-01-01',
                'role_id' => 'required|exists:roles,id',
                'calendar_category_id' => 'required|exists:calendar_categories,id',
                'photo' => 'required|image|mimes:jpeg,png,jpg',
                'card_photo' => 'required|image|mimes:jpeg,png,jpg',
            ]);

            // Process avatar
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $validated['photo'] = UploadHelper::upload($file, 'photo');
            }

            // Process card photo
            if ($request->hasFile('card_photo')) {
                $file = $request->file('card_photo');
                $validated['card_photo'] = UploadHelper::upload($file, 'cardPhoto');
            }

            $employee = Employee::create([
                ...$validated,
                'password' => bcrypt($validated['id']),
            ]);

            // $this->addAcs($employee);

            DB::commit();

            return response()->json([
                'message' => 'Employee created successfully!',
                'data' => $employee,
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            return HandleError::handle($e);
        }
    }

    public function addAcs($employee)
    {
        $deviceIp = config('acs.device_ip');
        $username = config('acs.username');
        $password = config('acs.password');

        $url = "http://{$deviceIp}/ISAPI/AccessControl/UserInfo/Record?format=json";

        $payload = [
            'UserInfo' => [
                'employeeNo' => $employee->id,
                'name' => $employee->name,
                'gender' => $employee->gender,
                'userType' => 'normal',
                'Valid' => [
                    'enable' => true,
                    'beginTime' => '2024-01-01T00:00:00',
                    'endTime' => '2030-12-31T23:59:59',
                    'timeType' => 'local',
                ],
                // "userVerifyMode" => "faceOrFpOrCardOrPw"
            ],
        ];

        $response = Http::withDigestAuth($username, $password)
            ->timeout(10)
            ->post($url, $payload);

        if (! $response->successful()) {
            return response()->json([
                'error' => 'Request failed',
                'details' => $response->body(),
            ], $response->status());
        }

        Log::info("ACS user [{$employee->id}] added successfully.");
    }

    public function updateEmployee(Request $request, $id)
    {
        DB::beginTransaction();
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
                ->whereNotIn('role_id', [15, 17])
                ->findOrFail($id);

            $validated = $request->validate([
                // 'id' => 'sometimes|string|unique:employees,id,' . $id,
                'name' => 'sometimes|string|max:255',
                'phone' => 'sometimes|string|regex:/^0[0-9]{9}$/|unique:employees,phone,'.$id,
                'email' => 'nullable|email|unique:employees,email,'.$id,
                'CCCD' => 'sometimes|string|regex:/^[0-9]+$/|unique:employees,cccd,'.$id,
                'address' => 'sometimes|string',
                'home_town' => 'sometimes|string',
                'birthday' => 'sometimes|date|before:today|after:1900-01-01',
                'gender' => 'sometimes|in:male,female,other',
                'marital_status' => 'sometimes|in:single,married,divorced,widowed',
                'company' => 'sometimes|in:vvp,a7a',
                'date_joining' => 'sometimes|date|after:2000-01-01',
                'role_id' => 'sometimes|exists:roles,id',
                'calendar_category_id' => 'sometimes|exists:calendar_categories,id',
                'photo' => 'sometimes|image|mimes:jpeg,png,jpg',
                'card_photo' => 'sometimes|image|mimes:jpeg,png,jpg',
            ]);

            $oldPhoto = $employee->photo;
            $oldCardPhoto = $employee->card_photo;

            // Process avatar
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $validated['photo'] = UploadHelper::upload($file, 'photo');
            }

            // Process card photo
            if ($request->hasFile('card_photo')) {
                $file = $request->file('card_photo');
                $validated['card_photo'] = UploadHelper::upload($file, 'cardPhoto');
            }

            $employee->update($validated);

            DB::commit();
            if ($oldPhoto) {
                Storage::delete('public/employee/'.$oldPhoto);
            }
            if ($oldCardPhoto) {
                Storage::delete('public/employee/'.$oldCardPhoto);
            }

            return response()->json([
                'message' => 'Employee updated successfully!',
                'data' => $employee,
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();

            return HandleError::handle($e);
        }
    }

    // Xóa nhân sự (Đưa vào thùng rác)
    public function removeEmployee($id)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::findOrFail($id);

            $employee->delete();

            DB::commit();

            return response()->json([
                'message' => 'Employee deleted successfully!',
                'data' => $employee,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return HandleError::handle($e);
        }
    }

    // Lấy danh sách nhân sự trong thùng rác
    public function getTrashEmployees(Request $request)
    {
        try {
            $employees = QueryBuilder::for(Employee::class)
                ->onlyTrashed()
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
                    'deleted_at',
                    'created_at',
                    'updated_at',
                )
                ->allowedFilters([
                    'name',
                    'phone',
                    'email',
                    'address',
                    'home_town',
                    'gender',
                    'birthday',
                    'CCCD',
                    'marital_status',
                    'date_joining',
                    'company',
                    'role_id',
                    'calendar_category_id',
                ])
                ->defaultSort('-created_at')
                ->allowedSorts([
                    'name',
                    'phone',
                    'email',
                    'address',
                    'home_town',
                    'birthday',
                    'CCCD',
                    'date_joining',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ])
                ->allowedIncludes(['role', 'calendarCategory'])
                ->whereNotIn('role_id', [15, 17]);

            $limit = $request->limit;
            if (! is_null($limit) && $limit == 0) {
                $limit = $employees->count();
            }
            $employees = $employees->paginate($limit ?? 10);

            return response()->json($employees);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    public function restoreEmployee($id)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::onlyTrashed()->findOrFail($id);

            $employee->restore();

            DB::commit();

            return response()->json([
                'message' => 'Employee restored successfully!',
                'data' => $employee,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return HandleError::handle($e);
        }
    }

    // Show Calander For Employee
    public function calendar(Request $request)
    {
        try {
            $calendars = Celender::query();

            if (! empty($request->key)) {
                $calendars->Name($request->key);
            }

            $total = count($calendars->get());

            $calendars = $calendars->orderBy('id', 'DESC')->paginate(Celender::paginate);

            LogActivity::logViewActivity(auth()->user(), 'Xem Lịch Làm Việc', 'Nhân viên xem lịch làm việc');

            return response()->json([
                'status' => 'success',
                'message' => 'Lấy danh sách lịch làm việc thành công.',
                'data' => $calendars,
                'total' => $total,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi xem lịch làm việc.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Show Calendar Detail For Employee
    public function calendarDetail($id)
    {
        try {
            $employeeId = auth()->user()->id;

            $calendarDetailHNHC = CelenderDetailHNHC::where('celender_id', $id)->where('employee_id', $employeeId)->first();
            $calendarDetailEatroom = CelenderDetailEatroom::where('celender_id', $id)->where('employee_id', $employeeId)->first();
            $calendarDetailWC = CelenderDetailWC::where('celender_id', $id)->where('employee_id', $employeeId)->first();
            $calendarDetailWCCleanWomen = CelenderDetailWCCleanWomen::where('celender_id', $id)->where('employee_id', $employeeId)->first();
            $calendarDetailWCCleanMen = CelenderDetailWCCleanMen::where('celender_id', $id)->where('employee_id', $employeeId)->first();

            $startDate = Celender::where('id', $id)->value('date');

            $dates = [];
            $month = date('m', strtotime($startDate));
            $monthNext = $month;
            $date = $startDate;

            while ($monthNext == $month) {
                $dates[] = $date;
                $date = date('Y/m/d', strtotime('+1 day', strtotime($date)));
                $monthNext = date('m', strtotime($date));
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Lấy chi tiết lịch làm việc thành công.',
                'data' => [
                    'calendarDetailHNHC' => $calendarDetailHNHC,
                    'calendarDetailEatroom' => $calendarDetailEatroom,
                    'calendarDetailWC' => $calendarDetailWC,
                    'calendarDetailWCCleanWomen' => $calendarDetailWCCleanWomen,
                    'calendarDetailWCCleanMen' => $calendarDetailWCCleanMen,
                    'calendar_id' => $id,
                    'dates' => $dates,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi lấy chi tiết lịch làm việc.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Show Salary For Employee
    public function salary(Request $request)
    {
        try {
            $user = auth()->user();
            $salaryManagers = SalaryManager::query();
            if (! empty($request->key)) {
                $salaryManagers->Name($request);
            }
            // $end_date = Carbon::now()->format('Y-m-d');
            // $salaryManagers = $salaryManagers->where('date_show', '<=', $end_date);
            $total = count($salaryManagers->get());
            $salaryManagers = $salaryManagers->orderBy('id', 'DESC')->paginate(SalaryManager::paginate);
            LogActivity::logViewActivity($user, 'Xem Bảng Lương', 'Nhân viên xem bảng lương');

            return response()->json([
                'status' => 'success',
                'message' => 'Lấy danh sách bảng lương thành công.',
                'data' => $salaryManagers,
                'total' => $total,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi lấy bảng lương.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Show Salary Detail For Employee
    public function salaryDetail($id)
    {
        try {
            $employee_id = auth()->user()->id;

            $salaryManager = SalaryManager::findOrFail($id);

            $salaryOfficialsVVP = SalaryOfficialVVP::where('salaries_manager_id', $id)
                ->where('employee_id', $employee_id)
                ->first();

            $salaryOfficialsA7A = SalaryOfficialA7A::where('salaries_manager_id', $id)
                ->where('employee_id', $employee_id)
                ->first();

            $actuallyReceived = $salaryOfficialsVVP->actually_received
                ?? $salaryOfficialsA7A->actually_received
                ?? $salaryParttimes->actually_received
                ?? 0;

            $salaryInWords = NumberToWordsHelper::convert($actuallyReceived);

            return response()->json([
                'status' => 'success',
                'message' => 'Lấy chi tiết bảng lương thành công.',
                'data' => [
                    'salaryManager' => $salaryManager,
                    'salaryOfficialsVVP' => $salaryOfficialsVVP,
                    'salaryOfficialsA7A' => $salaryOfficialsA7A,
                    'actuallyReceived' => $actuallyReceived,
                    'salaryInWords' => $salaryInWords,
                ],
            ]);
        } catch (\Exception $e) {
            LogHelper::saveLog('Xem chi tiết bảng lương', $e->getMessage(), $e->getLine());

            return response()->json([
                'status' => 'error',
                'message' => 'Xem chi tiết bảng lương không thành công!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
