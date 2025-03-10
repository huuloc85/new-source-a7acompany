<?php

namespace App\Http\Controllers;

use App\Models\CheckEmployee;
use App\Models\DailyQuantity;
use App\Models\Employee;
use App\Traits\CalenderTranslate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailyProductivityHistoryController extends Controller
{
    use CalenderTranslate;

    public function index(Request $request)
    {
        // Lấy ngày từ request hoặc sử dụng ngày hiện tại
        $date = $request->input('date', Carbon::today()->toDateString());
        $roleId = auth()->user()->role_id ?? null;

        if (in_array($roleId, [14, 18])) {
            $allowedStatuses = [1]; // Chỉ xem "Hàng 100%"
        } elseif ($roleId == 19) {
            $allowedStatuses = [2, 6]; // Chỉ xem "Hàng 200%" và "Hàng lỗi"
        } elseif ($roleId == 15) {
            $allowedStatuses = [1, 2, 6]; // Xem tất cả
        } else {
            $allowedStatuses = []; // Không xem được dữ liệu
        }
        // Lấy các product_id có trong bảng CheckEmployee cho ngày đã chọn và trạng thái $allowedStatuses
        $checkEmployeeProductIds = CheckEmployee::whereDate('date', $date)
            ->whereIn('status', $allowedStatuses)
            ->pluck('product_id');

        // Lấy các bản ghi từ DailyQuantity có product_id trong danh sách trên và nạp các mối quan hệ 'employee' và 'product'
        $dailyQuantities = DailyQuantity::with('employee', 'product')
            ->select('employee_id', 'product_id', 'date', 'status', DB::raw('SUM(quantity) as total_quantity'), DB::raw('MIN(created_at) as created_at'))
            ->whereDate('date', $date)
            ->whereIn('product_id', $checkEmployeeProductIds)
            ->whereIn('status', $allowedStatuses)
            ->groupBy('employee_id', 'product_id', 'date', 'status')
            ->get();

        // Lấy các bản ghi từ CheckEmployee
        $checkEmployees = CheckEmployee::with('employee', 'product')
            ->select('employee_id', 'product_id', 'date', 'shift', 'created_at')
            ->whereDate('date', $date)
            ->whereIn('status', $allowedStatuses)
            ->get();

        // Map dữ liệu từ DailyQuantity và CheckEmployee
        $productivityLogs = $dailyQuantities->map(function ($dailyQuantity) use ($checkEmployees) {
            // Tìm bản ghi tương ứng trong CheckEmployee
            $matchedEmployee = $checkEmployees->first(function ($checkEmployee) use ($dailyQuantity) {
                return $checkEmployee->employee_id == $dailyQuantity->employee_id
                    && $checkEmployee->product_id == $dailyQuantity->product_id
                    && $checkEmployee->date == $dailyQuantity->date;
            });

            // Format created_at thành H:i:s
            $createdAt = Carbon::parse($dailyQuantity->created_at)->format('H:i:s');

            $statusLabel = '';
            switch ($dailyQuantity->status) {
                case 1:
                    $statusLabel = 'Hàng 100%';
                    break;
                case 2:
                    $statusLabel = 'Hàng 200%';
                    break;
                default:
                    $statusLabel = 'Hàng Lỗi';
                    break;
            }

            return [
                'employee' => $dailyQuantity->employee,
                'product' => $dailyQuantity->product,
                'date' => Carbon::parse($dailyQuantity->date)->format('d-m-Y'),
                'total_quantity' => $dailyQuantity->total_quantity,
                'status' => $dailyQuantity->status,
                'shift' => $matchedEmployee ? $matchedEmployee->shift : '',
                'status_label' => $statusLabel,
                'created_at' => $createdAt,
            ];
        });

        // Lọc nhân viên chưa có logs
        $employeesWithoutLogs = $checkEmployees->reject(function ($checkEmployee) use ($dailyQuantities) {
            return $dailyQuantities->contains(function ($dailyQuantity) use ($checkEmployee) {
                return $dailyQuantity->employee_id == $checkEmployee->employee_id
                    && $dailyQuantity->product_id == $checkEmployee->product_id
                    && $dailyQuantity->date == $checkEmployee->date;
            });
        })->map(function ($checkEmployee) {
            return [
                'employee' => $checkEmployee->employee,
                'product' => $checkEmployee->product,
                'date' => Carbon::parse($checkEmployee->date)->format('d-m-Y'),
                'created_at' => Carbon::parse($checkEmployee->created_at)->format('H:i:s'),
                'shift' => $checkEmployee->shift,
            ];
        });

        return view('history.daily-update-quantity', [
            'productivityLogs' => $productivityLogs,
            'employeesWithoutLogs' => $employeesWithoutLogs,
            'selectedDate' => $date,
        ]);
    }
}
