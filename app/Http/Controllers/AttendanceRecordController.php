<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\CategoryCelender;
use App\Models\CelenderDetailHNHC;
use App\Models\Celender;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceRecordController extends Controller
{
    //View Lịch Sử Chấm Công (Admin)
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

    //View Bảng Tính Công (Admin)
    public function records(Request $request)
    {
        $currentMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $timeFilter = $request->time_filter ?? 'working_hours';
        $calendarId = Celender::whereMonth('date', Carbon::parse($currentMonth)->month)->pluck('id')->first();
        $query = $this->buildQuery($request, $currentMonth, $timeFilter);
        $records = $this->checkQuery($query, $timeFilter);
        $dayOfWeekMapping = AttendanceRecord::getDayOfWeekMapping();

        foreach ($records as $record) {
            $this->processRecord($record, $timeFilter, $dayOfWeekMapping, $calendarId);
        }
        return view('attendence.records', compact('records', 'currentMonth'));
    }

    //Query (Admin)
    private function buildQuery(Request $request, $currentMonth, $timeFilter)
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

        if (config("a7a.list_category")[$timeFilter]) {
            $categoryId = CategoryCelender::listCate[$timeFilter];
            $query->whereHas('employee', function ($query) use ($categoryId) {
                $query->where('category_celender_id', $categoryId);
            });

            if (in_array($timeFilter, config("a7a.list_category_ca1"))) {
                $query = $query->whereTime('time', '>=', config("a7a.ca1_start_time"))
                                ->whereTime('time', '<=', config("a7a.ca1_end_time"));
            }
        }

        return $query;
    }

    //Query theo ca (Admin)
    private function checkQuery($query, $timeFilter) {
        if (in_array($timeFilter, config("a7a.list_category_ca1"))) {
            $records = $query->select(
                'employee_code',
                'date',
                DB::raw('COUNT(*) as record_count'),
                DB::raw('MIN(time) as time_in'),
                DB::raw('MAX(time) as time_out')
            );
        } else if (in_array($timeFilter, config("a7a.list_category_ca2"))) {
            //nếu ca2
            $records = $query->select(
                'employee_code',
                'date',
                DB::raw('COUNT(*) as record_count'),
                DB::raw('MIN(time) as time_in'),
                DB::raw('MAX(time) as time_out'),
                DB::raw("GROUP_CONCAT(time ORDER BY time ASC SEPARATOR ', ') as all_times")
            );
        }

        return $records->groupBy('employee_code', 'date')
                        ->orderBy('employee_code', 'asc')
                        ->has('employee')
                        ->with('employee')
                        ->get();
    }

    //Code chức năng tính công (Admin)
    private function processRecord($record, $timeFilter, $dayOfWeekMapping, $calendarId)
    {
        $date = Carbon::parse($record->date);
        $record->day_of_week = $dayOfWeekMapping[$date->format('l')];
        if (in_array($timeFilter, config("a7a.list_category_ca1"))) {
            $this->processRecordCa1($record, $timeFilter, $dayOfWeekMapping);
        } elseif (in_array($timeFilter, config("a7a.list_category_ca2"))) {
            $shift = CelenderDetailHNHC::where('celender_id', $calendarId)->where('employee_id', $record->employee->id)->pluck('day'.$date->day)->first();
            if ($shift == config("a7a.shift_1")) {
                $this->processRecordCa1($record, $timeFilter, $dayOfWeekMapping);
            } else if ($shift == config("a7a.shift_2")) {
                $this->processRecordCa2($record, $timeFilter, $dayOfWeekMapping);
            } else {
                ///lịch nghĩ nhưng đi làm
                $date = $date->subDay();
                $shiftBefore = CelenderDetailHNHC::where('celender_id', $calendarId)->where('employee_id', $record->employee->id)->pluck('day'.$date->day)->first();
                if ($shiftBefore == config("a7a.shift_1")) {
                    $this->processRecordCa1($record, $timeFilter, $dayOfWeekMapping);
                } else if ($shiftBefore == config("a7a.shift_2")) {
                    $this->processRecordCa2($record, $timeFilter, $dayOfWeekMapping);
                }
            }
        }
    }

    //Code chức năng tính công ca 1 (Admin)
    private function processRecordCa1($record, $timeFilter, $dayOfWeekMapping) {
        $workStartTime = config("a7a.ca1_work_start_time");
        $workEndTime = $timeFilter === 'working_hours' ? config("a7a.ca1_work_end_time_wh") : config("a7a.ca1_work_end_time_qd");
        $breakTime = $timeFilter === 'working_hours' ? config("a7a.ca1_break_time_wh") : config("a7a.ca1_break_time_qd") ; // break time in minutes

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

        if ($record->time_in && $record->time_out) {
            $timeIn = Carbon::parse($record->time_in);
            $timeOut = Carbon::parse($record->time_out);

            // Nếu time_in và time_out khác nhau ít nhất 1 giờ
            if ($timeIn->diffInHours($timeOut) >= 1) {
                $record->total_hours = $this->calculateTotalHours($record, $workStartTime, $workEndTime, $breakTime);
                $record->overtime_hours = $this->calculateOvertime($record, $workStartTime, $workEndTime, $breakTime);
            } else {
                // Nếu không, đặt time_out là null
                $record->time_out = null;
                $record->total_hours = 0;
                $record->overtime_hours = 0;
            }
        }

        $administrativeHours = min($record->total_hours, config("a7a.time_work"));
        if ($record->employee->category_celender_id == 2) {
            $record->overtime_hours = max($record->total_hours - $administrativeHours, 0);
        }

        $record->administrative_hours = $administrativeHours;
        if ($record->overtime_hours > 0) {
            $record->total_hours = $record->administrative_hours + $record->overtime_hours;
        }
    }

    //Code chức năng tính công ca 2 (Admin)
    private function processRecordCa2($record, $timeFilter, $dayOfWeekMapping)
    {
        //set time in
        if ($record->record_count < 1) {
            $record->time_in = null;
        } else {
            $times = explode(', ', $record->all_times);
            $timesBefore8AM = array_filter($times, function($time) {
                return strtotime($time) > strtotime(config("a7a.ca2_min_start_time"));
            });
            $record->time_in = !empty($timesBefore8AM) ? min($timesBefore8AM) : null;
        }
        //set time out
        $checkTimeOut = AttendanceRecord::where('employee_code', $record->employee_code)
                                        ->where('date', Carbon::parse($record->date)->addDay())
                                        ->where('time', '<', config("a7a.ca2_max_end_time"))
                                        ->orderBy('time', 'desc')
                                        ->pluck('time')
                                        ->first();

        $record->time_out = isset($checkTimeOut) ? $checkTimeOut : null;

        $breakTime = config("a7a.ca2_break_time");
        $workStartTime = config("a7a.ca2_work_start_time");
        $workEndTime = config("a7a.ca2_work_end_time");
        $record->total_hours = $this->calculateTotalHours($record, $workStartTime, $workEndTime, $breakTime, true);
        $record->overtime_hours = $this->calculateOvertime($record, $workStartTime, $workEndTime, $breakTime, true);

        $administrativeHours = min($record->total_hours, 8);
        $record->administrative_hours = $administrativeHours;
        if ($record->overtime_hours > 0) {
            $record->total_hours = $record->administrative_hours + $record->overtime_hours;
        }
    }

    //Tính Tổng Giờ Làm Việc
    private function calculateTotalHours($record, $workStartTime, $workEndTime, $breakTime, $shift2 = false)
    {
        if (!$record->time_in || !$record->time_out) return 0;

        $timeInDate = Carbon::parse($record->time_in);
        $timeOutDate = Carbon::parse($record->time_out);
        $workStartDate = Carbon::parse($workStartTime);
        $workEndDate = Carbon::parse($workEndTime);

        $effectiveStart = $timeInDate < $workStartDate ? $workStartDate : $timeInDate;
        $effectiveEnd = $timeOutDate > $workEndDate ? $workEndDate : $timeOutDate;
        if ($shift2 && $effectiveEnd < $effectiveStart) {
            $effectiveEnd->addDay();
        }
        $workingMillis = max(0, $effectiveEnd->diffInSeconds($effectiveStart));
        $workingHours = $workingMillis / 3600 - ($breakTime / 60);

        $dailyWorkHours = 8;
        if ($workingHours < $dailyWorkHours) {
            $requiredHours = $dailyWorkHours - $workingHours;
            $timeOutDate = $shift2 == true ? $timeOutDate->subDay() : $timeOutDate;
            $billedHours = $timeOutDate <= $workEndDate ? 0 : min($timeOutDate->diffInHours($workEndDate), $requiredHours);
            $workingHours += $billedHours;
        }

        return round($workingHours * 4) / 4;
    }

    //Tính Giờ Tăng Ca
    private function calculateOvertime($record, $workStartTime, $workEndTime, $breakTime, $shift2 = false)
    {
        $totalHours = $record->total_hours;
        $categoryId = $record->employee->category_celender_id;
        $overtimeStart = Carbon::parse(config("a7a.over_time_start_qd"));
        if ($categoryId == CategoryCelender::listCate['working_hours']) {
            $overtimeStart = Carbon::parse(config("a7a.over_time_start_wh"));
        }
        if ($shift2) {
            $overtimeStart = Carbon::parse(Carbon::parse(config("a7a.over_time_start_ca2")));
        }
        if (!$overtimeStart) return 0;

        $overtimeEnd = $shift2 == false ? Carbon::parse(config("a7a.over_time_end_ca1")) : Carbon::parse(config("a7a.over_time_end_ca2"));
        $timeOut = Carbon::parse($record->time_out);

        if ($totalHours >= config("a7a.time_work")) {
            if ($timeOut->lt($overtimeStart)) return 0;
            if ($timeOut->gt($overtimeEnd)) $timeOut = $overtimeEnd;

            $overtimeMinutes = $timeOut->diffInMinutes($overtimeStart);
            $overtimeHours = $overtimeMinutes > 0 ? floor($overtimeMinutes / 15) * 0.25 : 0;

            return round($overtimeHours, 2);
        }

        return 0;
    }

    //View Lịch Sử Chấm Công (Nhân Viên)
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

    //View Tính Toán Chấm Công (Nhân Viên)
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

    //Kéo Data Từ Mcc
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
