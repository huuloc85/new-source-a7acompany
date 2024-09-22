<?php

namespace App\Http\Controllers;

use App\Models\LoginHistory;
use App\Models\Cele;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CelenderDetailHNHC;
use App\Models\Celender;
use App\Models\DailyQuantity;
use App\Traits\CalenderTranslate;

class LoginHistoryController extends Controller
{
    use CalenderTranslate;

    public function index(Request $request)
    {
        // Xác định ngày hiện tại và năm hiện tại
        $today = Carbon::today();
        $currentMonth = $today->month;
        $currentYear = $today->year;

        // Xử lý và tách tháng và năm từ request hoặc sử dụng giá trị hiện tại
        $selectedMonthYear = $request->input('month', $today->format('m-Y'));
        list($month, $year) = explode('-', $selectedMonthYear);

        // Xử lý ngày được chọn từ request, mặc định là ngày hiện tại
        $selectedDate = $request->input('date', $today->format('Y-m-d'));

        // Tạo danh sách các ngày trong tháng được chọn
        $daysInSelectedMonth = Carbon::createFromDate($year, $month)->daysInMonth;
        $days = [];
        for ($day = 1; $day <= $daysInSelectedMonth; $day++) {
            $days[] = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');
        }

        $date = Carbon::create($year, $month, substr($selectedDate, -2));
        $date = $date->format('Y-m-d');
        // Bắt đầu truy vấn và lọc dữ liệu đăng nhập
        $loginHistoryQuery = LoginHistory::query();
        $loginHistoryQuery->where('date', $date);

        // Lọc theo loại hoạt động nếu được chọn
        $selectedActivityType = $request->input('activity_type');
        if (!empty($selectedActivityType)) {
            $loginHistoryQuery->where('activity_type', $selectedActivityType);
        }

        $loginHistory = $loginHistoryQuery->paginate(LoginHistory::paginate);
        $totalHistoryCurrentPage = $loginHistory->sum('login_count');
        $totalHistoryOverall = LoginHistory::sum('login_count');

        // Lấy danh sách các loại hoạt động và các tháng có dữ liệu
        $activityTypes = LoginHistory::distinct()->pluck('activity_type');
        $monthsWithData = LoginHistory::selectRaw('DISTINCT MONTH(date) as month')
            ->whereYear('date', $currentYear)
            ->groupBy('month')
            ->get()
            ->pluck('month')
            ->map(function ($month) use ($currentYear) {
                return Carbon::createFromDate($currentYear, $month, 1)->format('m-Y');
            });

        // Lấy danh sách employee_id từ lịch sử đăng nhập
        $employeeIds = LoginHistory::distinct()->pluck('employee_id');

        // Tìm ID của Celender cho tháng hiện tại
        $celenderIds = Celender::whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->pluck('id');

        // Lấy thông tin lịch làm việc từ CelenderDetailHNHC
        $calendarDetails = CelenderDetailHNHC::whereIn('employee_id', $employeeIds)
            ->whereIn('celender_id', $celenderIds)
            ->latest()
            ->get();

        // Chuyển đổi giá trị lịch làm việc sang chuỗi tương ứng
        $translatedCalendarDetails = [];
        foreach ($calendarDetails as $calendarDetail) {
            $date = Carbon::parse($selectedDate)->format('d');
            $date = $this->convertDate($date);
            $column = 'day' . $date;
            $calendarDetailValue = $this->translateCalendar($calendarDetail->$column);
            $translatedCalendarDetails[$calendarDetail->employee_id] = $calendarDetailValue;
        }

        // Truyền dữ liệu vào view
        return view('history.index', [
            'loginHistory' => $loginHistory,
            'days' => $days,
            'selectedDate' => $selectedDate,
            'activityTypes' => $activityTypes,
            'months' => $monthsWithData,
            'selectedMonthYear' => $selectedMonthYear,
            'translatedCalendarDetails' => $translatedCalendarDetails,
            'totalHistoryCurrentPage' => $totalHistoryCurrentPage,
            'totalHistoryOverall' => $totalHistoryOverall
        ]);
    }

    public function deleteHistory(Request $request)
    {
        $date = $request->input('date');
        if ($date > now()->toDateString()) {
            toast('Không thể xóa lịch sử đăng nhập cho ngày này.', 'error', 'top-right');
            return redirect()->back();
        }
        try {
            LoginHistory::whereDate('date', $date)->delete();
            toast("Lịch sử đăng nhập cho ngày $date đã được xóa thành công!", 'success', 'top-right');
        } catch (\Exception $e) {
            toast('Có lỗi xảy ra khi xóa lịch sử đăng nhập.', 'error', 'top-right');
        }
        return redirect()->back();
    }

    public function viewAllQuantity(Request $request)
    {
        $selectedDate = $request->input('date', Carbon::today()->toDateString());
        $dailyQuantities = DailyQuantity::whereIn('status', [1, 2, 6])
            ->whereDate('date', $selectedDate)
            ->get();
        $translateStatus = function ($status) {
            switch ($status) {
                case 1:
                    return 'Hàng 100%';
                case 2:
                    return 'Hàng 200%';
                case 6:
                    return 'Hàng lỗi';
                default:
                    return 'Unknown';
            }
        };
        $formatDate = function ($date) {
            return Carbon::parse($date)->format('d-m-Y');
        };
        return view('history.history-view-all-quantity', [
            'dailyQuantities' => $dailyQuantities,
            'translateStatus' => $translateStatus,
            'formatDate' => $formatDate,
        ]);
    }
}
