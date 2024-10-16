<?php

namespace App\Http\Controllers;

use App\Exports\Attendance\MultiSheetExport;
use App\Exports\Attendance\Records;
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
use Maatwebsite\Excel\Facades\Excel;

class AttendanceRecordController extends Controller
{
    private $listRecord;
    //View Lịch Sử Chấm Công (Admin)
    public function index(Request $request)
    {
        $employees = Employee::whereNotIn('role_id', [15, 1])->get();
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

        return view('attendence.index', compact('records', 'currentMonth', 'categories', 'employees'));
    }

    //Add Record (Admin)
    public function handleAddRecords(Request $request)
    {
        DB::beginTransaction();
        try {
            // Tạo bản ghi mới
            $attendance = new AttendanceRecord();
            $attendance->employee_code = $request->input('employee_code');
            // $attendance->datetime = $request->input('datetime');
            $date = $request->input('date');
            $time = $request->input('time');
            $attendance->date = $date;
            $attendance->time = $time;
            $attendance->datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
            // $attendance->direction = $request->input('shift');
            // $attendance->deviceName = $request->input('deviceName');
            // $attendance->deviceSN = $request->input('shift');
            $attendance->employee_Name = $request->input('employee_name');
            // $attendance->cardNo = $request->input('shift');
            $attendance->save();

            DB::commit();
            toast('Thêm dữ liệu chấm công thành công!', 'success', 'top-right');
            return redirect()->route('admin.attendence.index');
        } catch (\Exception $e) {
            // Rollback nếu có lỗi
            DB::rollBack();
            Log::error('errors: ' . $e->getMessage() . ' getLine: ' . $e->getLine());
            toast('Thêm dữ liệu chấm công không thành công!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    //Delete Record (Admin)
    public function destroy($employee_code, $datetime)
    {
        try {
            $record = AttendanceRecord::where('employee_code', $employee_code)
                ->where('datetime', $datetime)
                ->first();

            if ($record) {
                AttendanceRecord::where('employee_code', $employee_code)
                    ->where('datetime', $datetime)
                    ->delete();
                Log::info("Đã xoá bản ghi chấm công: $employee_code vào lúc $datetime");
                toast('Đã xoá thành công bảng chấm công.', 'success');
            } else {
                Log::warning("Không tìm thấy bản ghi để xoá: $employee_code vào lúc $datetime");
                toast('Không tìm thấy bản ghi để xoá.', 'error');
            }
        } catch (\Exception $e) {
            Log::error("Xoá không thành công bảng chấm công: {$e->getMessage()}");
            toast('Xoá không thành công bảng chấm công.', 'error');
        }

        return redirect()->route('admin.attendence.index');
    }

    //View Bảng Tính Công (Admin)
    public function records(Request $request)
    {
        $currentMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $timeFilter = $request->time_filter ?? 'working_hours';
        $calendarId = Celender::whereMonth('date', Carbon::parse($currentMonth)->month)->pluck('id')->first();
        $query = $this->buildQuery($request, $currentMonth, $timeFilter);
        $records = $this->checkQuery($query, $timeFilter);
        $this->listRecord = $records;
        $dayOfWeekMapping = AttendanceRecord::getDayOfWeekMapping();

        foreach ($records as $key => $record) {
            $this->processRecord($record, $timeFilter, $dayOfWeekMapping, $calendarId, $key);
        }

        return view('attendence.records', [
            'records' => $this->listRecord,
            'currentMonth' => $currentMonth,
        ]);
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
    private function checkQuery($query, $timeFilter)
    {
        // if (in_array($timeFilter, config("a7a.list_category_ca1"))) {
        //     $records = $query->select(
        //         'employee_code',
        //         'date',
        //         DB::raw('COUNT(*) as record_count'),
        //         DB::raw('MIN(time) as time_in'),
        //         DB::raw('MAX(time) as time_out'),
        //         DB::raw("GROUP_CONCAT(time ORDER BY time ASC SEPARATOR ', ') as all_times")
        //     );
        // } else if (in_array($timeFilter, config("a7a.list_category_ca2"))) {
        //     //nếu ca2
        //     $records = $query->select(
        //         'employee_code',
        //         'date',
        //         DB::raw('COUNT(*) as record_count'),
        //         DB::raw('MIN(time) as time_in'),
        //         DB::raw('MAX(time) as time_out'),
        //         DB::raw("GROUP_CONCAT(time ORDER BY time ASC SEPARATOR ', ') as all_times")
        //     );
        // } else {
        //     //get all
        //     $records = $query->select(
        //         'employee_code',
        //         'date',
        //         DB::raw('COUNT(*) as record_count'),
        //         DB::raw('MIN(time) as time_in'),
        //         DB::raw('MAX(time) as time_out'),
        //         DB::raw("GROUP_CONCAT(time ORDER BY time ASC SEPARATOR ', ') as all_times")
        //     );
        // }
        $records = $query->select(
            'employee_code',
            'date',
            DB::raw('COUNT(*) as record_count'),
            DB::raw('MIN(time) as time_in'),
            DB::raw('MAX(time) as time_out'),
            DB::raw("GROUP_CONCAT(time ORDER BY time ASC SEPARATOR ', ') as all_times")
        );

        return $records->groupBy('employee_code', 'date')
            ->orderBy('employee_code', 'asc')
            ->has('employee')
            ->with('employee')
            ->get();
    }

    //Code chức năng tính công (Admin)
    private function processRecord($record, $timeFilter, $dayOfWeekMapping, $calendarId, $key)
    {
        if ($timeFilter == null && $record->employee->category_celender_id != null) {
            $timeFilter = CategoryCelender::listCateforEmployee[$record->employee->category_celender_id];
        }
        $date = Carbon::parse($record->date);
        $record->day_of_week = $dayOfWeekMapping[$date->format('l')];
        if (in_array($timeFilter, config("a7a.list_category_ca1"))) {
            $this->processRecordCa1($record, $timeFilter, $dayOfWeekMapping);
        } elseif (in_array($timeFilter, config("a7a.list_category_ca2"))) {
            $shift = CelenderDetailHNHC::where('celender_id', $calendarId)->where('employee_id', $record->employee->id)->pluck('day' . $date->day)->first();
            $record->shift = ($shift === config("a7a.shift_1") || $shift === config("a7a.shift_1_extra_day"))
                ? 'Ca 1'
                : (($shift === config("a7a.shift_2") || $shift === config("a7a.shift_2_extra_night"))
                    ? 'Ca 2'
                    : 'Đổi lịch đi làm');
            if ($shift == config("a7a.shift_1") || $shift == config("a7a.shift_1_extra_day")) {
                $this->processRecordCa1($record, $timeFilter, $dayOfWeekMapping);
            } else if ($shift == config("a7a.shift_2") || $shift == config("a7a.shift_2_extra_night")) {
                if (Carbon::now()->format('Y-m-d') == $record->date) {
                    unset($this->listRecord[$key]);
                } else {
                    $times = explode(', ', $record->all_times);
                    $timesBefore12AM = array_filter($times, function ($time) {
                        return strtotime($time) < strtotime(config("a7a.ca2_check_start_time"));
                    });

                    $timesAfter12AM = array_filter($times, function ($time) {
                        return strtotime($time) > strtotime(config("a7a.ca2_check_start_time"));
                    });

                    if (empty($timesBefore12AM) || !empty($timesAfter12AM)) {
                        $this->processRecordCa2($record, $timeFilter, $dayOfWeekMapping);
                    } else {
                        unset($this->listRecord[$key]);
                    }
                }
            } else {
                ///lịch nghĩ nhưng đi làm
                if ($date->day == 1) {
                    ///get new category_id
                    $prevMonth = $date = $date->subDay();
                    $calendarId = Celender::whereMonth('date', Carbon::parse($prevMonth)->month)->pluck('id')->first();
                }
                $date = $date->subDay();
                $shiftBefore = CelenderDetailHNHC::where('celender_id', $calendarId)->where('employee_id', $record->employee->id)->pluck('day' . $date->day)->first();
                if ($shiftBefore == config("a7a.shift_1") || $shiftBefore == config("a7a.shift_1_extra_day")) {
                    $this->processRecordCa1($record, $timeFilter, $dayOfWeekMapping);
                } else if ($shiftBefore == config("a7a.shift_2") || $shiftBefore == config("a7a.shift_2_extra_night")) {
                    if (Carbon::now()->format('Y-m-d') == $record->date) {
                        unset($this->listRecord[$key]);
                    } else {
                        // check in before 12h AM?
                        $times = explode(', ', $record->all_times);
                        $timesBefore12AM = array_filter($times, function ($time) {
                            return strtotime($time) < strtotime(config("a7a.ca2_check_start_time"));
                        });
                        $timesAfter12AM = array_filter($times, function ($time) {
                            return strtotime($time) > strtotime(config("a7a.ca2_check_start_time"));
                        });

                        if (empty($timesBefore12AM) || !empty($timesAfter12AM)) {
                            $this->processRecordCa2($record, $timeFilter, $dayOfWeekMapping);
                        } else {
                            unset($this->listRecord[$key]);
                        }
                    }
                }
            }
        }
    }

    //Tính Tổng BreakTime
    private function calculateBreakTime($timeFilter, $timeIn, $timeOut)
    {
        $breakTimesConfig = config("a7a.break_times");
        $breakTime = 0;
        $timeIn = Carbon::parse($timeIn);
        $timeOut = Carbon::parse($timeOut);

        switch ($timeFilter) {
            case 'working_hours':
                $breakSchedule = $breakTimesConfig['ca_hanh_chinh']['schedule'];
                break;
            case 'qc_day':
                $breakSchedule = $breakTimesConfig['ca_ngay']['schedule'];
                break;
            case 'rotating_shift_mk':
            case 'rotating_shift_jp':
            case 'technical':
                // Chọn lịch nghỉ dựa trên giờ vào
                $breakSchedule = $timeIn->hour >= 21 ?
                    $breakTimesConfig['sx_ca_2']['schedule'] :
                    $breakTimesConfig['sx_ca_1']['schedule'];
                break;
            default:
                return 0;
        }

        foreach ($breakSchedule as $break) {
            $breakStart = Carbon::parse($break['time']);
            $breakEnd = $breakStart->copy()->addMinutes($break['duration']);

            // Kiểm tra xem khoảng nghỉ có nằm trong khoảng thời gian làm việc không
            if ($breakStart < $timeOut && $breakEnd > $timeIn) {
                // Tính thời gian nghỉ thực tế
                $actualBreakStart = max($breakStart, $timeIn);
                $actualBreakEnd = min($breakEnd, $timeOut);
                $breakTime += $actualBreakEnd->diffInMinutes($actualBreakStart);
            }
        }

        return $breakTime;
    }

    //Code chức năng tính công ca 1 (Admin)
    private function processRecordCa1($record, $timeFilter, $dayOfWeekMapping)
    {
        $workStartTime = config("a7a.ca1_work_start_time");
        $workEndTime = $timeFilter === 'working_hours' ? config("a7a.ca1_work_end_time_wh") : config("a7a.ca1_work_end_time_qd");
        $breakTime = $this->calculateBreakTime($timeFilter, $record->time_in, $record->time_out);

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

        if ($record->record_count > 1) {
            $timeIn = Carbon::parse($record->time_in);
            $timeOut = Carbon::parse($record->time_out);

            //Khoảng cách giữa time_out và time_out > 1 thì chạy bình thường
            if ($timeIn && $timeOut && $timeIn->diffInHours($timeOut) <= 1) {
                $diffToStart = $timeIn->diffInSeconds(Carbon::parse($workStartTime));
                $diffToEnd = $timeIn->diffInSeconds(Carbon::parse($workEndTime));

                if ($diffToStart < $diffToEnd) {
                    $record->time_in = $timeIn;
                    $record->time_out = null;
                } else {
                    $record->time_out = $timeIn;
                    $record->time_in = null;
                }
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
        if ($record->employee->category_celender_id == [2, 4]) {
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
            $timesBefore8AM = array_filter($times, function ($time) {
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

        if ($record->time_out == null && $record->time_in != null) {
            $times = explode(', ', $record->all_times);
            if (count($times) > 1) {
                $listTimeout = array_filter($times, function ($time) use ($record) {
                    return strtotime($time) > strtotime($record->time_in);
                });
                $record->time_out = !empty($listTimeout) ? min($listTimeout) : null;
            }
        }

        $breakTime = $this->calculateBreakTime($timeFilter, $record->time_in, $record->time_out);
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

        // Không cộng thêm ngày nếu về sớm trước 24h
        if ($shift2 && $timeOutDate->hour < 24 && $timeOutDate->isSameDay($timeInDate)) {
            $effectiveEnd = $timeOutDate;
        }

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
        $employeeCode = auth()->user()->code;
        $categoryId = auth()->user()->category_celender_id;
        $currentMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $calendarId = Celender::whereMonth('date', Carbon::parse($currentMonth)->month)->pluck('id')->first();
        $dayOfWeekMapping = AttendanceRecord::getDayOfWeekMapping();
        $query = AttendanceRecord::whereYear('date', Carbon::parse($currentMonth)->year)
            ->whereMonth('date', Carbon::parse($currentMonth)->month)
            ->where('employee_code', $employeeCode)
            ->orderBy('employee_code', 'asc')
            ->orderBy('date', 'asc');
        $timeFilter = CategoryCelender::listCateforEmployee[$categoryId];
        $records = $this->checkQuery($query, $timeFilter);
        $this->listRecord = $records;
        foreach ($records as $key => $record) {
            $this->processRecord($record, $timeFilter, $dayOfWeekMapping, $calendarId, $key);
        }
        return view('attendence.employee_caculate_records', [
            'records' => $this->listRecord,
            'currentMonth' => $currentMonth,
        ]);
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

    //Export
    public function export(Request $request)
    {
        $currentMonth = Carbon::now()->format('m-Y');
        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
        $employeeCodes = Employee::whereNotIn('role_id', [15, 1])->pluck('code');
        $calendarId = Celender::whereBetween('date', [$startDate, $endDate])->pluck('id')->first();
        $query = AttendanceRecord::whereBetween('date', [$startDate, $endDate])
            ->whereIn('employee_code', $employeeCodes)
            ->orderBy('employee_code', 'asc')
            ->orderBy('date', 'asc');
        $records = $this->checkQuery($query, null);
        $this->listRecord = $records;
        $a7aRecords = [];
        $vinhVinhPhatRecords = [];

        foreach ($records as $key => $record) {
            $employee = Employee::where('code', $record->employee_code)->first();
            if ($employee) {
                if ($employee->company === 'A7A') {
                    $a7aRecords[] = $record;
                } elseif ($employee->company === 'Vinh Vinh Phát') {
                    $vinhVinhPhatRecords[] = $record;
                }
            }
            $this->processRecord($record, null, AttendanceRecord::getDayOfWeekMapping(), $calendarId, $key);
        }
        return Excel::download(
            new MultiSheetExport([
                'A7A' => $a7aRecords,
                'Vinh Vinh Phát' => $vinhVinhPhatRecords,
            ], $startDate, $endDate, $currentMonth), // Thêm $startDate, $endDate và $currentMonth
            'Bảng Tính Công Tháng ' . $currentMonth . '.xlsx',
            \Maatwebsite\Excel\Excel::XLSX,
            [
                'Content-Type' => 'text/xlsx',
            ]
        );
    }

    //TestView Export
    public function testExport(Request $request)
    {
        // Lấy ngày bắt đầu và ngày kết thúc từ request
        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

        // Lấy mã nhân viên, loại trừ các vai trò 15 và 1, chia theo công ty
        $employeesA7A = Employee::where('company', 'A7A')
            ->whereNotIn('role_id', [15, 1])
            ->pluck('code');
        $employeesVinhVinhPhat = Employee::where('company', 'Vinh Vinh Phát')
            ->whereNotIn('role_id', [15, 1])
            ->pluck('code');

        // Lấy calendar ID cho phạm vi ngày được chọn
        $calendarId = Celender::whereBetween('date', [$startDate, $endDate])
            ->pluck('id')
            ->first();

        // Truy vấn bản ghi chấm công cho công ty A7A
        $queryA7A = AttendanceRecord::whereBetween('date', [$startDate, $endDate])
            ->whereIn('employee_code', $employeesA7A)
            ->orderBy('employee_code', 'asc')
            ->orderBy('date', 'asc');

        // Truy vấn bản ghi chấm công cho công ty Vinh Vinh Phát
        $queryVinhVinhPhat = AttendanceRecord::whereBetween('date', [$startDate, $endDate])
            ->whereIn('employee_code', $employeesVinhVinhPhat)
            ->orderBy('employee_code', 'asc')
            ->orderBy('date', 'asc');

        // Lấy mapping ngày trong tuần
        $dayOfWeekMapping = AttendanceRecord::getDayOfWeekMapping();

        // Kiểm tra và xử lý dữ liệu cho công ty A7A
        $recordsA7A = $this->checkQuery($queryA7A, null);
        foreach ($recordsA7A as $key => $record) {
            $this->processRecord($record, null, $dayOfWeekMapping, $calendarId, $key);
        }

        // Kiểm tra và xử lý dữ liệu cho công ty Vinh Vinh Phát
        $recordsVinhVinhPhat = $this->checkQuery($queryVinhVinhPhat, null);
        foreach ($recordsVinhVinhPhat as $key => $record) {
            $this->processRecord($record, null, $dayOfWeekMapping, $calendarId, $key);
        }

        // Trả về view với dữ liệu được chia thành hai sheet
        return view('export.attendance.records', [
            'recordsA7A' => $recordsA7A,
            'recordsVinhVinhPhat' => $recordsVinhVinhPhat,
        ]);
    }
}
