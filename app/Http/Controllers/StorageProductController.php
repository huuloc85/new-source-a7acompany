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
        $availableDates = StorageProduct::selectRaw('DATE(created_at) as date')
            ->distinct()
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->map(fn ($d) => Carbon::parse($d)->toDateString())
            ->toArray();

        $filterDate = $request->input('filter_date', $availableDates[0] ?? now()->toDateString());

        $storage = StorageProduct::with(['product', 'employee'])
            ->whereDate('created_at', $filterDate)
            ->when($request->filled('product_id'), fn ($q) => $q->where('product_id', $request->input('product_id')))
            ->when($request->filled('employee_id'), fn ($q) => $q->where('employee_id', $request->input('employee_id')))
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->created_at)->format('Y-m-d').'|'.$item->employee_id.'|'.$item->product_id;
            });

        $productIds = StorageProduct::distinct()->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->orderBy('name')->get();

        $employeeIds = StorageProduct::distinct()->pluck('employee_id');
        $employees = Employee::whereIn('id', $employeeIds)->orderBy('name')->get();

        $lotModalData = null;

        if ($request->filled('lot') && $request->filled('lot_product_id')) {
            $lotCode = strtoupper(trim($request->input('lot')));
            if (! str_starts_with($lotCode, 'A-')) {
                $lotCode = 'A-'.$lotCode;
            }

            // Chuẩn hóa lot thành A-18062025-2-065
            $lotCode = preg_replace_callback('/^A-(\d{8})-(\d+)-(\d{1,3})$/', function ($matches) {
                return "A-{$matches[1]}-{$matches[2]}-".str_pad($matches[3], 3, '0', STR_PAD_LEFT);
            }, $lotCode);

            $productId = (int) $request->input('lot_product_id');

            if (! preg_match('/^A-(\d{2})(\d{2})(\d{4})-([12])-(\d{3})$/', $lotCode, $matches)) {
                $lotModalData = ['error' => 'Mã lot không đúng định dạng.'];
            } else {
                [$all, $day, $month, $year, $shift, $endSerial] = $matches;

                $lotPrefix = "A-{$day}{$month}{$year}-{$shift}-";
                $endNumber = (int) ltrim($endSerial, '0') ?: 1;

                $product = Product::find($productId);
                if (! $product) {
                    $lotModalData = ['error' => 'Không tìm thấy sản phẩm.'];
                } else {
                    // Lấy danh sách các thùng thực tế đã có
                    $existingLots = StorageProduct::where('product_id', $productId)
                        ->where('lot', 'like', "$lotPrefix%")
                        ->pluck('lot');

                    $existingNumbers = $existingLots->map(function ($lot) use ($lotPrefix) {
                        return (int) ltrim(str_replace($lotPrefix, '', $lot), '0') ?: 1;
                    })->unique()->sort()->values();

                    // Lấy số bắt đầu là thùng nhỏ nhất thực tế, nếu không có thì bắt đầu từ 1
                    $startNumber = $existingNumbers->min() ?? 1;

                    // Giới hạn endNumber không nhỏ hơn start
                    if ($endNumber < $startNumber) {
                        $lotModalData = ['error' => 'Số thùng kết thúc nhỏ hơn số đã có.'];
                    } else {
                        // Tính khoảng số thùng mong đợi
                        $expectedNumbers = range($startNumber, $endNumber);
                        $missingNumbers = array_diff($expectedNumbers, $existingNumbers->all());

                        $missingLots = array_map(
                            fn ($num) => $lotPrefix.str_pad($num, 3, '0', STR_PAD_LEFT),
                            $missingNumbers
                        );

                        $lotModalData = [
                            'code' => $lotCode,
                            'product' => $product->name,
                            'date' => "$day/$month/$year",
                            'expected' => count($expectedNumbers),
                            'startFrom' => $startNumber,
                            'endAt' => $endNumber,
                            'actual' => count($expectedNumbers) - count($missingNumbers),
                            'missing' => count($missingNumbers),
                            'missingLots' => $missingLots,
                            'status' => count($missingNumbers) > 0 ? 'warning' : 'success',
                        ];
                    }
                }
            }
        }

        return view('storage.product', compact(
            'storage',
            'products',
            'employees',
            'availableDates',
            'filterDate',
            'lotModalData'
        ));
    }
}
