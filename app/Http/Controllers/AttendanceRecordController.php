<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceRecordController extends Controller
{
    public function index(Request $request)
    {
        $records = AttendanceRecord::orderBy('employee_code', 'asc')
            ->orderBy('auth_d', 'asc')
            ->get();

        return view('attendence.index', compact('records'));
    }

    public function employeeview(Request $request)
    {
        try {
            // Lấy employee_id từ người dùng hiện tại
            $employeeId = Auth::user()->id;

            // Tìm nhân viên dựa trên employee_id
            $employee = Employee::where('id', $employeeId)->first();

            // Nếu không tìm thấy nhân viên, trả về lỗi hoặc thông báo
            if (!$employee) {
                throw new \Exception('Không tìm thấy thông tin nhân viên.');
            }

            // Lấy mã nhân viên (employee_code) và category_calendar_id
            $employeeCode = $employee->code;
            $categoryCalendarId = $employee->category_celender_id;

            // Lấy tên nhân viên
            $employeeName = $employee->name;

            // Tạo truy vấn chấm công cho nhân viên hiện tại
            $query = AttendanceRecord::where('employee_code', $employeeCode);

            // Lọc theo khoảng thời gian từ "start_date" đến "end_date"
            if ($request->has('start_date') && $request->start_date) {
                $query->whereDate('auth_d', '>=', $request->start_date);
            }
            if ($request->has('end_date') && $request->end_date) {
                $query->whereDate('auth_d', '<=', $request->end_date);
            }

            // Lọc theo tháng của cột `auth_d`
            if ($request->has('month_filter') && $request->month_filter) {
                $month = Carbon::parse($request->month_filter)->format('m-Y');
                $query->whereRaw("DATE_FORMAT(auth_d, '%Y-%m') = ?", [Carbon::parse($request->month_filter)->format('Y-m')]);
            } else {
                // Nếu không có lọc tháng, lấy tháng từ dữ liệu
                $records = $query->get();
                $month = $records->count() > 0 ? Carbon::parse($records->first()->auth_d)->format('m-Y') : now()->format('m-Y');
            }

            // Lọc theo thời gian cho ca hành chính và QC
            if ($categoryCalendarId === 4) { // Ca hành chính
                $startTime = '06:00:00';
                $endTime = '20:30:00';
                $query->whereTime('auth_t', '>=', $startTime)
                    ->whereTime('auth_t', '<=', $endTime);
            } elseif ($categoryCalendarId === 2) { // QC Ca Ngày
                $startTime = '06:00:00';
                $endTime = '20:30:00';
                $query->whereTime('auth_t', '>=', $startTime)
                    ->whereTime('auth_t', '<=', $endTime);
            }

            // Lấy tất cả bản ghi mà không nhóm
            $records = $query->orderBy('auth_d', 'asc')->get();

            // Nhóm theo ngày và mã nhân viên để lấy thời gian vào (Time In) và ra (Time Out)
            $groupedRecords = $records->groupBy('auth_d')->map(function ($dayRecords) {
                // Tạo đối tượng bản ghi mới cho mỗi ngày
                $record = new \stdClass();
                $record->auth_d = $dayRecords->first()->auth_d;
                $record->time_in = null;
                $record->time_out = null;
                $record->status = '';

                if ($dayRecords->count() == 1) {
                    // Nếu chỉ có một bản ghi
                    $record->time_in = $dayRecords->first()->auth_t;
                    $record->time_out = 'Chưa Chấm Công Ra';
                    $record->status = 'text-danger';
                } else {
                    // Nếu có nhiều bản ghi, tính toán thời gian vào và ra
                    $record->time_in = $dayRecords->min('auth_t');
                    $record->time_out = $dayRecords->max('auth_t');
                }

                return $record;
            });

            // Đổi lại tên cột và thêm thông tin ngày
            $dayOfWeekMapping = [
                'Monday'    => 'Thứ Hai',
                'Tuesday'   => 'Thứ Ba',
                'Wednesday' => 'Thứ Tư',
                'Thursday'  => 'Thứ Năm',
                'Friday'    => 'Thứ Sáu',
                'Saturday'  => 'Thứ Bảy',
                'Sunday'    => 'Chủ Nhật'
            ];

            foreach ($groupedRecords as $record) {
                $date = Carbon::parse($record->auth_d);
                $record->formatted_date = $date->format('d-m-Y');
                $record->day_of_week = $dayOfWeekMapping[$date->format('l')];
            }

            return view('attendence.employee_records', [
                'records' => $groupedRecords,
                'employeeName' => $employeeName,
                'month' => $month
            ]);
        } catch (\Exception $e) {
            // Ghi lỗi vào log
            Log::error('errors: ' . $e->getMessage() . ' - getLine: ' . $e->getLine());

            // Xử lý lỗi và trả về thông báo lỗi
            return redirect()->back()->with('error', 'Đã xảy ra lỗi trong khi lấy thông tin chấm công: ' . $e->getMessage());
        }
    }
}
