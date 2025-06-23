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
            $input = strtoupper(trim($request->input('lot')));

            // Thêm "A-" nếu chưa có
            if (! str_starts_with($input, 'A-')) {
                $input = 'A-'.$input;
            }

            // Chuẩn hoá: A-05062025-2-18 => A-05062025-2-018
            $lotCode = preg_replace_callback('/^A-(\d{8})-(\d+)-(\d{1,3})$/', function ($matches) {
                $datePart = $matches[1];        // 05062025
                $batchPart = $matches[2];       // 2
                $serialPart = str_pad($matches[3], 3, '0', STR_PAD_LEFT);  // 18 => 018

                return "A-{$datePart}-{$batchPart}-{$serialPart}";
            }, $input);

            $productId = (int) $request->input('lot_product_id');

            if (! preg_match('/^A-([0-9]{2})([0-9]{2})([0-9]{4})-([12])-([0-9]{3})$/', $lotCode, $matches)) {
                $lotModalData = ['error' => 'Mã lot không đúng định dạng.'];
            } else {
                [$all, $day, $month, $year, $shift, $qtyStr] = $matches;

                $expected = (int) ltrim($qtyStr, '0') ?: 1; // số thùng mong đợi
                $lotPrefix = "A-$day$month$year-$shift-";

                $product = Product::find($productId);
                if (! $product) {
                    $lotModalData = ['error' => 'Không tìm thấy sản phẩm.'];
                } else {
                    // Lấy tất cả thùng trong cùng lot (dựa theo prefix)
                    $existingNumbers = StorageProduct::where('product_id', $productId)
                        ->where('lot', 'like', "{$lotPrefix}%")
                        ->pluck('lot')
                        ->map(fn ($lot) => (int) ltrim(str_replace($lotPrefix, '', $lot), '0') ?: 1)
                        ->unique()
                        ->sort()
                        ->values();

                    $startNumber = $existingNumbers->min() ?? 1; // lấy thùng đầu tiên thực tế
                    $expectedNumbers = range($startNumber, $startNumber + $expected - 1);
                    $missingNumbers = array_diff($expectedNumbers, $existingNumbers->all());

                    $missingLots = array_map(
                        fn ($num) => $lotPrefix.str_pad($num, 3, '0', STR_PAD_LEFT),
                        $missingNumbers
                    );

                    $lotModalData = [
                        'code' => $lotCode,
                        'product' => $product->name,
                        'date' => "$day/$month/$year",
                        'expected' => $expected,
                        'actual' => count($expectedNumbers) - count($missingNumbers),
                        'missing' => count($missingNumbers),
                        'missingLots' => $missingLots,
                        'status' => count($missingNumbers) > 0 ? 'warning' : 'success',
                    ];
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
