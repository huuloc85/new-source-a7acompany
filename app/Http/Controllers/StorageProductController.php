<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Product;
use App\Models\StorageProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StorageProductController extends Controller
{
    public function index(Request $request)
    {
        // Lấy ngày mới nhất có trong bảng storage_products
        $latestRecord = StorageProduct::latest('created_at')->first();

        // Nếu có dữ liệu, lấy tháng/năm từ bản ghi mới nhất
        $month = $latestRecord ? $latestRecord->created_at->month : now()->month;
        $year = $latestRecord ? $latestRecord->created_at->year : now()->year;

        // Nếu người dùng có chọn tháng/năm thủ công thì ưu tiên
        if ($request->filled('month')) {
            $month = (int) $request->input('month');
        }

        // Khởi tạo query chính
        $query = StorageProduct::with(['product', 'employee'])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year);

        // Lọc theo sản phẩm nếu có
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }

        // Lọc theo nhân viên nếu có
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->input('employee_id'));
        }

        // Lọc theo ngày (từ ngày đến ngày) nếu có
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->input('start_date'))->startOfDay(),
                Carbon::parse($request->input('end_date'))->endOfDay(),
            ]);
        }

        // Lấy danh sách dữ liệu sau khi lọc
        $storage = $query->get();

        // Lấy danh sách các tháng có dữ liệu
        $months = StorageProduct::selectRaw('DISTINCT MONTH(created_at) as month')
            ->orderBy('month')
            ->pluck('month');

        // Lấy danh sách sản phẩm theo dữ liệu có trong storage_products
        $productIds = StorageProduct::distinct()->pluck('product_id');
        $products = Product::whereIn('id', $productIds)
            ->orderBy('name')
            ->get();

        // Lấy danh sách nhân viên theo dữ liệu có trong storage_products
        $employeeIds = StorageProduct::distinct()->pluck('employee_id');
        $employees = Employee::whereIn('id', $employeeIds)
            ->orderBy('name')
            ->get();

        // Trả về view với tất cả dữ liệu
        return view('storage.product', compact(
            'storage',
            'products',
            'employees',
            'months',
            'month',
            'year'
        ));
    }
}
