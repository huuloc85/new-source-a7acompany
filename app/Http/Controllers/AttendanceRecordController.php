<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\CategoryCelender;
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
        // Lấy tháng hiện tại hoặc tháng được chọn từ request
        $currentMonth = $request->input('month', Carbon::now()->format('Y-m'));

        // Lấy danh sách các danh mục làm việc
        $categories = CategoryCelender::all();

        // Truy vấn dữ liệu theo tháng được chọn và danh mục (nếu có)
        $query = AttendanceRecord::whereYear('date', Carbon::parse($currentMonth)->year)
            ->whereMonth('date', Carbon::parse($currentMonth)->month)
            ->orderBy('employee_code', 'asc')
            ->orderBy('date', 'asc');

        if ($request->filled('category')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('category_celender_id', $request->input('category'));
            });
        }

        $records = $query->get();

        return view('attendence.index', compact('records', 'currentMonth', 'categories'));
    }

    public function records(Request $request)
    {
        $currentMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $timeFilter = $request->time_filter ?? 'working_hours';
        $startTime = '06:00:00';
        $endTime = '20:30:00';

        $query = $this->buildQuery($request, $currentMonth, $timeFilter, $startTime, $endTime);

        $records = $query->select(
            'employee_code',
            'date',
            DB::raw('COUNT(*) as record_count'),
            DB::raw('MIN(time) as time_in'),
            DB::raw('MAX(time) as time_out')
        )
            ->groupBy('employee_code', 'date')
            ->orderBy('employee_code', 'asc')
            ->with('employee')
            ->get();

        $dayOfWeekMapping = AttendanceRecord::getDayOfWeekMapping();

        foreach ($records as $record) {
            $this->processRecord($record, $timeFilter, $dayOfWeekMapping);
        }

        return view('attendence.records', compact('records', 'currentMonth'));
    }

    private function buildQuery(Request $request, $currentMonth, $timeFilter, $startTime, $endTime)
    {
        $query = AttendanceRecord::whereYear('date', Carbon::parse($currentMonth)->year)
            ->whereMonth('date', Carbon::parse($currentMonth)->month)
            ->orderBy('employee_code', 'asc')
            ->orderBy('date', 'asc');

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }
        if ($request->has('employee_name') && $request->employee_name) {
            $employeeIds = Employee::where('name', 'like', '%' . $request->employee_name . '%')->pluck('code');
            $query->whereIn('employee_code', $employeeIds);
        }

        if (in_array($timeFilter, ['working_hours', 'qc_day'])) {
            $categoryId = $timeFilter === 'working_hours' ? 4 : 2;
            $query->whereHas('employee', function ($query) use ($categoryId) {
                $query->where('category_celender_id', $categoryId);
            })
                ->whereTime('time', '>=', $startTime)
                ->whereTime('time', '<=', $endTime);
        }

        return $query;
    }

    private function processRecord($record, $timeFilter, $dayOfWeekMapping)
    {
        $date = Carbon::parse($record->date);
        $record->day_of_week = $dayOfWeekMapping[$date->format('l')];

        $workStartTime = '07:30:00';
        $workEndTime = $timeFilter === 'working_hours' ? '17:00:00' : '19:30:00';
        $breakTime = $timeFilter === 'working_hours' ? 90 : 60; // break time in minutes

        if ($record->record_count == 1) {
            $time = Carbon::parse($record->time_in);
            $diffToStart = $time->diffInSeconds(Carbon::parse($workStartTime));
            $diffToEnd = $time->diffInSeconds(Carbon::parse($workEndTime));

            if ($diffToStart < $diffToEnd) {
                $record->time_in = $time;
                $record->time_out = null;
            } else {
                $record->time_out = $time;
                $record->time_in = null;
            }
        }

        $record->total_hours = $this->calculateTotalHours($record->time_in, $record->time_out, $workStartTime, $workEndTime, $breakTime);
        $record->overtime_hours = $this->calculateOvertime($record->time_out, $record->employee->category_celender_id, $record->time_in, $workStartTime, $workEndTime, $breakTime);

        $administrativeHours = min($record->total_hours, 8);
        if ($record->employee->category_celender_id == 2) {
            $record->overtime_hours = max($record->total_hours - $administrativeHours, 0);
        }

        $record->administrative_hours = $administrativeHours;
        if ($record->overtime_hours > 0) {
            $record->total_hours = $record->administrative_hours + $record->overtime_hours;
        }
    }

    private function calculateTotalHours($timeIn, $timeOut, $workStartTime, $workEndTime, $breakTime)
    {
        if (!$timeIn || !$timeOut) return 0;

        $timeInDate = Carbon::parse($timeIn);
        $timeOutDate = Carbon::parse($timeOut);
        $workStartDate = Carbon::parse($workStartTime);
        $workEndDate = Carbon::parse($workEndTime);

        $effectiveStart = $timeInDate < $workStartDate ? $workStartDate : $timeInDate;
        $effectiveEnd = $timeOutDate > $workEndDate ? $workEndDate : $timeOutDate;
        $workingMillis = max(0, $effectiveEnd->diffInSeconds($effectiveStart));
        $workingHours = $workingMillis / 3600 - ($breakTime / 60);

        $dailyWorkHours = 8;
        if ($workingHours < $dailyWorkHours) {
            $requiredHours = $dailyWorkHours - $workingHours;
            $billedHours = $timeOutDate <= $workEndDate ? 0 : min($timeOutDate->diffInHours($workEndDate), $requiredHours);
            $workingHours += $billedHours;
        }

        return round($workingHours * 4) / 4;
    }

    private function calculateOvertime($timeOut, $categoryId, $timeIn, $workStartTime, $workEndTime, $breakTime)
    {
        $totalHours = $this->calculateTotalHours($timeIn, $timeOut, $workStartTime, $workEndTime, $breakTime);
        $overtimeStart = $categoryId == 2 ? Carbon::parse('16:30:00') : ($categoryId == 4 ? Carbon::parse('17:00:00') : null);
        if (!$overtimeStart) return 0;

        $overtimeEnd = Carbon::parse('20:30:00');
        $timeOut = Carbon::parse($timeOut);

        if ($totalHours >= 8) {
            if ($timeOut->lt($overtimeStart)) return 0;
            if ($timeOut->gt($overtimeEnd)) $timeOut = $overtimeEnd;

            $overtimeMinutes = $timeOut->diffInMinutes($overtimeStart);
            $overtimeHours = $overtimeMinutes > 0 ? floor($overtimeMinutes / 15) * 0.25 : 0;

            return round($overtimeHours, 2);
        }

        return 0;
    }

    public function employeeViewRecords(Request $request)
    {
        $employeeCode = auth()->user()->code;
        // Lấy tháng hiện tại hoặc tháng được chọn từ request
        $currentMonth = $request->input('month', Carbon::now()->format('Y-m'));

        // Lấy danh sách các danh mục làm việc
        $categories = CategoryCelender::all();

        // Truy vấn dữ liệu theo tháng được chọn và danh mục (nếu có)
        $query = AttendanceRecord::whereYear('date', Carbon::parse($currentMonth)->year)
            ->whereMonth('date', Carbon::parse($currentMonth)->month)
            ->where('employee_code', $employeeCode)
            ->orderBy('date', 'asc');
        $records = $query->get();

        return view('attendence.employee_records', compact('records', 'currentMonth', 'categories'));
    }

    public function employeeViewCaculateRecords(Request $request)
    {
        $employeeCode = auth()->user()->code;  // Lọc theo mã nhân viên đăng nhập
        $employeeCategory = auth()->user()->category_celender_id;
        $currentMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $startTime = '06:00:00';
        $endTime = '20:30:00';

        // Xây dựng truy vấn với điều kiện lọc employee_code
        $query = AttendanceRecord::whereYear('date', Carbon::parse($currentMonth)->year)
            ->whereMonth('date', Carbon::parse($currentMonth)->month)
            ->where('employee_code', $employeeCode)  // Chỉ lấy dữ liệu của nhân viên hiện tại
            ->orderBy('date', 'asc');

        // Lấy dữ liệu bản ghi chấm công
        $records = $query->select(
            'employee_code',
            'date',
            DB::raw('COUNT(*) as record_count'),
            DB::raw('MIN(time) as time_in'),
            DB::raw('MAX(time) as time_out')
        )
            ->groupBy('employee_code', 'date')
            ->orderBy('employee_code', 'asc')
            ->with('employee')
            ->get();

        // Mapping ngày trong tuần
        $dayOfWeekMapping = AttendanceRecord::getDayOfWeekMapping();

        // Xử lý dữ liệu của từng bản ghi dựa trên loại nhân viên
        foreach ($records as $record) {
            $this->processRecord($record, $employeeCategory, $dayOfWeekMapping);
        }

        return view('attendence.employee_caculate_records', compact('records', 'currentMonth'));
    }

    public function updateDataCC(Request $request)
    {
        try {
            $data = $request->input('data');
            if (!empty($data)) {
                foreach ($data as $record) {
                    $check = DB::table('attendencerecord')
                        ->where('employee_code', $record['employee_code'])
                        ->where('datetime', $record['datetime'])
                        ->first();

                    if (!$check) {
                        DB::table('attendencerecord')->insert($record);
                    } else {
                        Log::error("bản ghi đã tồn tại employee_code:" . $record['employee_code'] . " datetime: " . $record['datetime']);
                    }
                }
                return response()->json(['message' => 'Data inserted successfully']);
            } else {
                return response()->json(['message' => 'No data to insert'], 400);
            }
        } catch (\Exception $e) {
            Log::error("error: " . $e);
            return response()->json(['message' => 'No data to insert'], 500);
        }
    }
}
