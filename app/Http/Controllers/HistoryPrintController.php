<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\HistoryPrint;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryPrintController extends Controller
{

    public function index(Request $request)
    {
        $query = HistoryPrint::query();

        if (Auth::user()->role_id != 15) {
            if (Auth::user()->code != '19010400') {
                $query->where('employee_id', Auth::user()->id);
            }
        }
        $this->applyFilters($query, $request);

        $historyprint = $query->get();

        // Lấy danh sách loại tem, sản phẩm và nhân viên
        $distinctValues = $this->getDistinctValues($request);

        return view('barcode.history', array_merge(
            ['historyprint' => $historyprint],
            $distinctValues
        ));
    }

    // Phương thức để áp dụng các bộ lọc
    private function applyFilters($query, Request $request)
    {
        // Lọc theo tháng
        if ($request->has('month') && $request->month != '') {
            $query->whereMonth('date', Carbon::parse($request->month)->month);
        } else {
            $query->whereMonth('date', Carbon::now()->month);
        }

        // Lọc theo loại tem
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // Lọc theo sản phẩm
        if ($request->has('product_id') && $request->product_id != '') {
            $query->where('product_id', $request->product_id);
        }

        // Lọc theo nhân viên
        if ($request->has('employee_id') && $request->employee_id != '') {
            $query->where('employee_id', $request->employee_id);
        }

        // Lọc theo ngày cụ thể
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('date', Carbon::parse($request->date));
        }
    }

    // Phương thức để lấy danh sách loại tem, sản phẩm, và nhân viên
    private function getDistinctValues(Request $request)
    {
        $query = HistoryPrint::query();

        // Áp dụng bộ lọc theo user
        if (Auth::user()->role_id != 15 && Auth::user()->code != '19010400') {
            $query->where('employee_id', Auth::user()->id);
        }

        // Lọc theo tháng nếu được chọn
        if ($request->has('month') && $request->month != '') {
            $query->whereMonth('date', Carbon::parse($request->month)->month)
                ->whereYear('date', Carbon::parse($request->month)->year);
        } else {
            $query->whereMonth('date', Carbon::now()->month)
                ->whereYear('date', Carbon::now()->year);
        }

        // Lấy loại tem (distinct)
        $types = $query->clone()
            ->select('type')
            ->distinct()
            ->pluck('type')->filter()->unique();

        // Lấy sản phẩm (distinct)
        $products = $query->clone()
            ->select('product_id')
            ->distinct()
            ->pluck('product_id')
            ->map(function ($id) {
                return Product::find($id);
            })->filter();

        // Lấy nhân viên (distinct)
        $employees = $query->clone()
            ->select('employee_id')
            ->distinct()
            ->pluck('employee_id')
            ->map(function ($id) {
                return Employee::find($id);
            })->filter();

        // Lấy danh sách ngày dựa theo tháng đã chọn (distinct)
        $dates = $query->clone()
            ->select('date')
            ->distinct()
            ->pluck('date')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })->unique();

        return compact('types', 'products', 'employees', 'dates');
    }
}
