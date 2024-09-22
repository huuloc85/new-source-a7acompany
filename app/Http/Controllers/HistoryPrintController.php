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
        $distinctValues = $this->getDistinctValues();

        return view('barcode.history', array_merge(
            ['historyprint' => $historyprint],
            $distinctValues
        ));
    }

    // Phương thức để áp dụng các bộ lọc
    private function applyFilters($query, Request $request)
    {
        if ($request->has('month') && $request->month != '') {
            $query->whereMonth('created_at', Carbon::parse($request->month)->month);
        } else {
            $query->whereMonth('created_at', Carbon::now()->month);
        }
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        if ($request->has('product_id') && $request->product_id != '') {
            $query->where('product_id', $request->product_id);
        }

        if ($request->has('employee_id') && $request->employee_id != '') {
            $query->where('employee_id', $request->employee_id);
        }
    }

    // Phương thức để lấy danh sách loại tem, sản phẩm, và nhân viên
    private function getDistinctValues()
    {
        $query = HistoryPrint::distinct()
            ->where(function ($q) {
                if (Auth::user()->role_id != 15 && Auth::user()->code != '19010400') {
                    $q->where('employee_id', Auth::user()->id);
                }
            });

        // Lấy loại tem
        $types = $query->clone()->pluck('type')->filter()->unique();

        // Lấy sản phẩm
        $products = $query->clone()
            ->pluck('product_id')
            ->map(function ($id) {
                return Product::find($id);
            })->filter();

        // Lấy nhân viên
        $employees = $query->clone()
            ->pluck('employee_id')
            ->map(function ($id) {
                return Employee::find($id);
            })->filter();

        return compact('types', 'products', 'employees');
    }
}
