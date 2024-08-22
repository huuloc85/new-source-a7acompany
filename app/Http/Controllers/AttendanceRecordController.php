<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceRecordController extends Controller
{
    public function index(Request $request)
    {
        $currentMonth = Carbon::create(2024, 7, 1)->format('m-Y');
        $query = AttendanceRecord::query();

        // Gán giá trị mặc định cho `time_filter` nếu không có trong request
        $timeFilter = $request->time_filter ?? 'working_hours';

        // Lọc theo khoảng thời gian từ "start_date" đến "end_date"
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('auth_d', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('auth_d', '<=', $request->end_date);
        }

        // Lọc theo tên nhân viên
        if ($request->has('employee_name') && $request->employee_name) {
            $employeeIds = Employee::where('name', 'like', '%' . $request->employee_name . '%')->pluck('code');
            $query->whereIn('employee_code', $employeeIds);
        }

        // Lọc theo thời gian cho ca hành chính và category_calender_id
        if ($timeFilter === 'working_hours') {
            $startTime = '06:00:00';
            $endTime = '20:30:00';

            $query->whereTime('auth_t', '>=', $startTime)
                ->whereTime('auth_t', '<=', $endTime)
                ->whereHas('employee', function ($query) {
                    $query->where('category_celender_id', 4);
                });
        } elseif ($timeFilter === 'qc_day') {
            $startTime = '06:00:00';
            $endTime = '20:30:00';

            $query->whereTime('auth_t', '>=', $startTime)
                ->whereTime('auth_t', '<=', $endTime)
                ->whereHas('employee', function ($query) {
                    $query->where('category_celender_id', 2);
                });
        } elseif ($timeFilter === 'nhan_vien_san_xuat_shift_1') {
            $startTime = '06:00:00';
            $endTime = '20:30:00';

            $query->whereTime('auth_t', '>=', $startTime)
                ->whereTime('auth_t', '<=', $endTime)
                ->whereHas('employee', function ($query) {
                    $query->whereIn('category_celender_id', [5, 6]);
                });
        } elseif ($timeFilter === 'nhan_vien_san_xuat_shift_2') {
            $startTimeEvening = '18:00:00';
            $endTimeMorning = '08:30:00';

            $query->where(function ($query) use ($startTimeEvening, $endTimeMorning) {
                $query->whereTime('auth_t', '>=', $startTimeEvening)
                    ->orWhereTime('auth_t', '<=', $endTimeMorning);
            })->whereHas('employee', function ($query) {
                $query->whereIn('category_celender_id', [5, 6]);
            });
        }

        // Nhóm theo ngày và mã nhân viên để lấy thời gian vào (Time In) và ra (Time Out)
        $records = $query->select(
            'employee_code',
            'auth_d',
            DB::raw('COUNT(*) as record_count'),
            DB::raw('MIN(auth_t) as time_in'),
            DB::raw('MAX(auth_t) as time_out')
        )
            ->groupBy('employee_code', 'auth_d')
            ->orderBy('employee_code', 'asc')
            ->with('employee')
            ->get();

        // Thêm cột thứ (ngày trong tuần) và định dạng ngày
        $dayOfWeekMapping = [
            'Monday'    => 'Thứ Hai',
            'Tuesday'   => 'Thứ Ba',
            'Wednesday' => 'Thứ Tư',
            'Thursday'  => 'Thứ Năm',
            'Friday'    => 'Thứ Sáu',
            'Saturday'  => 'Thứ Bảy',
            'Sunday'    => 'Chủ Nhật'
        ];

        foreach ($records as $record) {
            $date = Carbon::parse($record->auth_d);
            $record->formatted_date = $date->format('d-m-Y');
            $record->day_of_week = $dayOfWeekMapping[$date->format('l')];

            // Nếu chỉ có 1 bản ghi thì áp dụng time_filter để gán time_in hoặc time_out
            if ($record->record_count == 1) {
                if ($timeFilter === 'working_hours' || $timeFilter === 'qc_day' || $timeFilter === 'nhan_vien_san_xuat_shift_1') {
                    // Gán time_in
                    $record->time_in = $record->time_in;
                    $record->time_out = null;
                } elseif ($timeFilter === 'nhan_vien_san_xuat_shift_2') {
                    // Gán time_out
                    $record->time_in = null;
                    $record->time_out = $record->time_out;
                }
            }
        }

        return view('attendence.index', compact('records', 'currentMonth'));
    }
}
