<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Celender;
use App\Models\CelenderDetailHNHC;
use App\Models\DailyQuantity;
use App\Models\LoginHistory;
use App\Traits\CalenderTranslate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    use CalenderTranslate;

    public function index(Request $request)
    {
        try {
            $today = Carbon::today();
            $currentMonth = $today->month;
            $currentYear = $today->year;

            $selectedMonthYear = $request->input('month', $today->format('m-Y'));
            [$month, $year] = explode('-', $selectedMonthYear);

            $selectedDate = $request->input('date', $today->format('Y-m-d'));

            $daysInSelectedMonth = Carbon::createFromDate($year, $month)->daysInMonth;
            $days = [];
            for ($day = 1; $day <= $daysInSelectedMonth; $day++) {
                $days[] = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');
            }

            $date = Carbon::create($year, $month, substr($selectedDate, -2))->format('Y-m-d');

            $loginHistoryQuery = LoginHistory::query()->where('date', $date);

            $selectedActivityType = $request->input('activity_type');
            if (! empty($selectedActivityType)) {
                $loginHistoryQuery->where('activity_type', $selectedActivityType);
            }

            $totalHistoryCurrentPage = $loginHistoryQuery->sum('login_count');
            $loginHistory = $loginHistoryQuery->paginate(LoginHistory::paginate);
            $totalHistoryOverall = LoginHistory::sum('login_count');

            $activityTypes = LoginHistory::distinct()->pluck('activity_type');

            $monthsWithData = LoginHistory::selectRaw('DISTINCT MONTH(date) as month')
                ->whereYear('date', $currentYear)
                ->groupBy('month')
                ->get()
                ->pluck('month')
                ->map(function ($month) use ($currentYear) {
                    return Carbon::createFromDate($currentYear, $month, 1)->format('m-Y');
                });

            $employeeIds = LoginHistory::distinct()->pluck('employee_id');

            $celenderIds = Celender::whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->pluck('id');

            $calendarDetails = CelenderDetailHNHC::whereIn('employee_id', $employeeIds)
                ->whereIn('celender_id', $celenderIds)
                ->latest()
                ->get();

            $translatedCalendarDetails = [];
            foreach ($calendarDetails as $calendarDetail) {
                $dayStr = Carbon::parse($selectedDate)->format('d');
                $column = 'day'.$this->convertDate($dayStr);
                $calendarDetailValue = $this->translateCalendar($calendarDetail->$column);
                $translatedCalendarDetails[$calendarDetail->employee_id] = $calendarDetailValue;
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Lấy dữ liệu thành công.',
                'data' => [
                    'loginHistory' => $loginHistory,
                    'days' => $days,
                    'selectedDate' => $selectedDate,
                    'activityTypes' => $activityTypes,
                    'months' => $monthsWithData,
                    'selectedMonthYear' => $selectedMonthYear,
                    'translatedCalendarDetails' => $translatedCalendarDetails,
                    'totalHistoryCurrentPage' => $totalHistoryCurrentPage,
                    'totalHistoryOverall' => $totalHistoryOverall,
                ],
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy dữ liệu.',
            ], 404);
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không được phép truy cập. Vui lòng đăng nhập.',
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau.',
            ], 400);
        }
    }

    // delete history by date
    public function destroy(Request $request)
    {
        $date = $request->input('date');
        if (! $date || $date > now()->toDateString()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ngày không hợp lệ hoặc là ngày trong tương lai.',
            ], 422);
        }
        try {
            $deleted = LoginHistory::whereDate('date', $date)->delete();

            if ($deleted) {
                return response()->json([
                    'status' => 'success',
                    'message' => "Lịch sử đăng nhập cho ngày $date đã được xóa thành công!",
                ], 200);
            } else {
                // 404: Not Found
                return response()->json([
                    'status' => 'error',
                    'message' => "Không tìm thấy lịch sử đăng nhập cho ngày $date.",
                ], 404);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Xung đột dữ liệu trong quá trình xóa (database conflict).',
            ], 409);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi nội bộ máy chủ khi xóa lịch sử.',
            ], 500);
        }
    }

    // view all quantity
    public function viewLogAllQuantity(Request $request)
    {
        try {
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

            return response()->json([
                'status' => 'success',
                'message' => 'Lấy dữ liệu thành công.',
                'data' => [
                    'dailyQuantities' => $dailyQuantities,
                    'selectedDate' => $selectedDate,
                    'formatDate' => $formatDate,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu.',
            ], 400);
        }
    }
}
