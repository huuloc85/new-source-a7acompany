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
        $query = AttendanceRecord::whereYear('date', Carbon::parse($currentMonth)->year)
            ->whereMonth('date', Carbon::parse($currentMonth)->month)
            ->orderBy('employee_code', 'asc')
            ->orderBy('date', 'asc');

        $timeFilter = $request->time_filter ?? 'working_hours';

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

        $startTime = '06:00:00';
        $endTime = '20:30:00';

        if (in_array($timeFilter, ['working_hours', 'qc_day'])) {
            $categoryId = $timeFilter === 'working_hours' ? 4 : 2;
            $workStartTime = $timeFilter === 'working_hours' ? '07:30:00' : '07:30:00';
            $workEndTime = $timeFilter === 'working_hours' ? '17:00:00' : '19:30:00';
            $breakTime = $timeFilter === 'working_hours' ? 90 : 60;

            $query->whereHas('employee', function ($query) use ($categoryId) {
                $query->where('category_celender_id', $categoryId);
            });

            $records = $query->select(
                'employee_code',
                'date',
                DB::raw('COUNT(*) as record_count'),
                DB::raw('MIN(time) as time_in'),
                DB::raw('MAX(time) as time_out')
            )
                ->whereTime('time', '>=', $startTime)
                ->whereTime('time', '<=', $endTime)
                ->groupBy('employee_code', 'date')
                ->orderBy('employee_code', 'asc')
                ->with('employee')
                ->get();
        } else {
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
        }

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
            $date = Carbon::parse($record->date);
            $record->day_of_week = $dayOfWeekMapping[$date->format('l')];

            $workStartTime = $timeFilter === 'working_hours' ? '07:30:00' : '07:30:00';
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

            // Tính Tổng Giờ Làm
            $record->total_hours = $this->calculateTotalHours($record->time_in, $record->time_out, $workStartTime, $workEndTime, $breakTime);

            // Tính Giờ Tăng Ca
            $record->overtime_hours = $this->calculateOvertime($record->time_out, $record->employee->category_celender_id, $record->time_in, $workStartTime, $workEndTime, $breakTime);
            // dd($record->overtime_hours);

            // Tính Giờ Hành Chính
            $administrativeHours = min($record->total_hours, 8);

            if ($record->employee->category_celender_id == 2) {
                $record->overtime_hours = max($record->total_hours - $administrativeHours, 0);
            }

            // Gán giờ hành chính
            $record->administrative_hours = $administrativeHours;
        }

        return view('attendence.records', compact('records', 'currentMonth'));
    }


    private function calculateTotalHours($timeIn, $timeOut, $workStartTime, $workEndTime, $breakTime)
    {
        if (!$timeIn || !$timeOut) return 0;

        $timeInDate = Carbon::parse($timeIn);
        $timeOutDate = Carbon::parse($timeOut);
        $workStartDate = Carbon::parse($workStartTime);
        $workEndDate = Carbon::parse($workEndTime);

        // Thay đổi thời gian vào nếu nhân viên đến trễ
        $effectiveStart = $timeInDate < $workStartDate ? $workStartDate : $timeInDate;

        // Nếu thời gian ra ngoài trễ hơn thời gian kết thúc làm việc, điều chỉnh thời gian kết thúc
        $effectiveEnd = $timeOutDate > $workEndDate ? $workEndDate : $timeOutDate;

        // Tính tổng thời gian làm việc giữa thời gian vào và ra
        $workingMillis = max(0, $effectiveEnd->diffInSeconds($effectiveStart));
        $workingHours = $workingMillis / (60 * 60) - ($breakTime / 60);

        // Đảm bảo tổng giờ làm việc ít nhất là 8 giờ
        $dailyWorkHours = 8;
        if ($workingHours < $dailyWorkHours) {
            // Nếu tổng giờ làm việc chưa đủ 8 giờ, tính giờ bù
            $requiredHours = $dailyWorkHours - $workingHours;

            // Tính giờ bù nếu nhân viên không về sớm hơn thời gian kết thúc công việc
            if ($timeOutDate <= $workEndDate) {
                // Nếu thời gian ra ngoài không muộn hơn thời gian kết thúc làm việc, không tính giờ bù
                $billedHours = 0;
            } else {
                $extraTime = $timeOutDate->diffInHours($workEndDate);
                $billedHours = min($extraTime, $requiredHours);
            }

            $workingHours += $billedHours;
        }

        // Làm tròn tổng giờ làm việc về nửa giờ gần nhất
        return round($workingHours * 4) / 4;
    }

    private function calculateOvertime($timeOut, $categoryId, $timeIn, $workStartTime, $workEndTime, $breakTime)
    {
        // Tính tổng giờ làm việc đầu tiên
        $totalHours = $this->calculateTotalHours($timeIn, $timeOut, $workStartTime, $workEndTime, $breakTime);

        // Xác định giờ làm thêm bắt đầu dựa trên categoryId
        switch ($categoryId) {
            case 2:
                $overtimeStart = Carbon::parse('16:30:00');
                break;
            case 4:
                $overtimeStart = Carbon::parse('17:00:00');
                break;
            default:
                return 0;
        }

        $overtimeEnd = Carbon::parse('19:30:00');
        $timeOut = Carbon::parse($timeOut);

        // Nếu đã làm đủ 8 giờ, tính giờ làm thêm từ thời gian tương ứng
        if ($totalHours >= 8) {
            if ($timeOut->lt($overtimeStart)) {
                return 0; // Không tính giờ tăng ca nếu trước thời gian overtimeStart
            }

            if ($timeOut->gt($overtimeEnd)) {
                $timeOut = $overtimeEnd; // Chỉ tính giờ làm thêm đến 19:30
            }

            // Tính số phút làm thêm từ $overtimeStart đến $timeOut
            $overtimeMinutes = $timeOut->diffInMinutes($overtimeStart);

            // Tính số giờ làm thêm dựa trên số phút đã qua và làm tròn đến mốc 15 phút
            $overtimeHours = 0;
            if ($overtimeMinutes > 0) {
                // Mỗi 16 phút thêm 0.25 giờ
                $overtimeHours = floor($overtimeMinutes / 16) * 0.25;
            }

            return round($overtimeHours, 2); // Làm tròn đến 2 chữ số thập phân
        }

        return 0;
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
                $query->whereDate('date', '>=', $request->start_date);
            }
            if ($request->has('end_date') && $request->end_date) {
                $query->whereDate('date', '<=', $request->end_date);
            }

            // Lọc theo tháng của cột `date`
            if ($request->has('month_filter') && $request->month_filter) {
                $month = Carbon::parse($request->month_filter)->format('m-Y');
                $query->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [Carbon::parse($request->month_filter)->format('Y-m')]);
            } else {
                // Nếu không có lọc tháng, lấy tháng từ dữ liệu
                $records = $query->get();
                $month = $records->count() > 0 ? Carbon::parse($records->first()->date)->format('m-Y') : now()->format('m-Y');
            }

            // Lọc theo thời gian cho ca hành chính và QC
            if ($categoryCalendarId === 4) { // Ca hành chính
                $startTime = '06:00:00';
                $endTime = '20:30:00';
                $query->whereTime('time', '>=', $startTime)
                    ->whereTime('time', '<=', $endTime);
            } elseif ($categoryCalendarId === 2) { // QC Ca Ngày
                $startTime = '06:00:00';
                $endTime = '20:30:00';
                $query->whereTime('time', '>=', $startTime)
                    ->whereTime('time', '<=', $endTime);
            }

            // Lấy tất cả bản ghi mà không nhóm
            $records = $query->orderBy('date', 'asc')->get();

            // Nhóm theo ngày và mã nhân viên để lấy thời gian vào (Time In) và ra (Time Out)
            $groupedRecords = $records->groupBy('date')->map(function ($dayRecords) {
                // Tạo đối tượng bản ghi mới cho mỗi ngày
                $record = new \stdClass();
                $record->date = $dayRecords->first()->date;
                $record->time_in = null;
                $record->time_out = null;
                $record->status = '';

                if ($dayRecords->count() == 1) {
                    // Nếu chỉ có một bản ghi
                    $record->time_in = $dayRecords->first()->time;
                    $record->time_out = 'Chưa Chấm Công Ra';
                    $record->status = 'text-danger';
                } else {
                    // Nếu có nhiều bản ghi, tính toán thời gian vào và ra
                    $record->time_in = $dayRecords->min('time');
                    $record->time_out = $dayRecords->max('time');
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
                $date = Carbon::parse($record->date);
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
