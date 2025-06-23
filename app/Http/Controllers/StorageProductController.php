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
        // Lấy danh sách ngày có dữ liệu
        $availableDates = StorageProduct::selectRaw('DATE(created_at) as date')
            ->distinct()
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->map(fn ($d) => Carbon::parse($d)->toDateString())
            ->toArray();

        // Ngày được chọn hoặc mặc định là ngày mới nhất có dữ liệu
        $filterDate = $request->input('filter_date', $availableDates[0] ?? now()->toDateString());

        // Truy vấn dữ liệu lọc đúng theo ngày (không dùng whereBetween)
        $storage = StorageProduct::with(['product', 'employee'])
            ->whereDate('created_at', $filterDate)
            ->when($request->filled('product_id'), fn ($q) => $q->where('product_id', $request->input('product_id')))
            ->when($request->filled('employee_id'), fn ($q) => $q->where('employee_id', $request->input('employee_id')))
            ->orderByDesc('bin')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->created_at)->format('Y-m-d').'|'.$item->employee_id.'|'.$item->product_id;
            });

        // Tháng để lọc thủ công (nếu dùng)
        $months = StorageProduct::selectRaw('DISTINCT MONTH(created_at) as month')
            ->orderBy('month')
            ->pluck('month');

        // Danh sách sản phẩm
        $productIds = StorageProduct::distinct()->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->orderBy('name')->get();

        // Danh sách nhân viên
        $employeeIds = StorageProduct::distinct()->pluck('employee_id');
        $employees = Employee::whereIn('id', $employeeIds)->orderBy('name')->get();

        return view('storage.product', compact(
            'storage',
            'products',
            'employees',
            'months',
            'availableDates',
            'filterDate'
        ));
    }
}
