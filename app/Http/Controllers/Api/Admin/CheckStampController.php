<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Product;
use App\Models\SendStamp;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CheckStampController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = SendStamp::query();

            // Nếu không có ngày được chọn, mặc định lấy ngày hiện tại
            $date = $request->get('date', Carbon::today()->toDateString());
            $query->whereDate('created_at', $date);

            // Lọc theo sản phẩm
            if ($request->filled('product_name')) {
                $query->whereHas('product', function ($q) use ($request) {
                    $q->where('name', $request->product_name);
                });
            }

            // Lọc theo nhân viên
            if ($request->filled('employee_name')) {
                $query->whereHas('employee', function ($q) use ($request) {
                    $q->where('name', $request->employee_name);
                });
            }

            // Lọc theo ca làm việc
            if ($request->filled('shift')) {
                $query->where('shift', $request->shift);
            }

            // Lọc theo trạng thái
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Lấy danh sách sản phẩm từ dữ liệu đã lọc
            $productIds = $query->pluck('product_id')->unique();
            $products = Product::whereIn('id', $productIds)->get();

            // Lấy danh sách nhân viên từ dữ liệu đã lọc
            $employeeIds = $query->pluck('employee_id')->unique();
            $employees = Employee::whereIn('id', $employeeIds)->get();

            // Lấy danh sách ca và trạng thái từ dữ liệu đã lọc
            $availableShifts = $query->pluck('shift')->unique()->values();
            $availableStatuses = $query->pluck('status')->unique()->values();

            $historyprint = $query->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Lấy dữ liệu kiểm tra tem thành công.',
                'data' => [
                    'historyprint' => $historyprint,
                    'products' => $products,
                    'employees' => $employees,
                    'availableShifts' => $availableShifts,
                    'availableStatuses' => $availableStatuses,
                    'date' => $date,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi khi truy xuất dữ liệu.',
            ], 400);
        }
    }
}
