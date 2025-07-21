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
        // 1. Lấy ngày mới nhất từ dữ liệu để làm mặc định
        $latestDate = StorageProduct::selectRaw('DATE(created_at) as date')
            ->orderBy('date', 'desc')
            ->first()?->date;

        $defaultDate = $latestDate ? Carbon::parse($latestDate)->toDateString() : now()->toDateString();

        // 2. Xác định ngày filter - ưu tiên ngày mới nhất nếu không có filter
        $filterDate = $request->input('filter_date', $defaultDate);

        // 3. Xác định tháng filter dựa trên ngày đã chọn
        $filterMonth = $request->input('filter_month');
        if (! $filterMonth && $filterDate) {
            $filterMonth = Carbon::parse($filterDate)->format('Y-m');
        }

        // 4. Lấy tất cả các tháng có sẵn từ dữ liệu
        $availableMonths = StorageProduct::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month')
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month')
            ->toArray();

        // 5. Lấy các ngày có sẵn dựa trên tháng đã chọn
        $availableDatesQuery = StorageProduct::selectRaw('DATE(created_at) as date')
            ->distinct()
            ->orderBy('date', 'desc');

        if ($filterMonth) {
            $availableDatesQuery->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$filterMonth]);
        }

        $availableDates = $availableDatesQuery->pluck('date')
            ->map(fn ($d) => Carbon::parse($d)->toDateString())
            ->toArray();

        // 6. Validate filterDate có trong availableDates không
        if (! in_array($filterDate, $availableDates)) {
            $filterDate = $availableDates[0] ?? $defaultDate;
        }

        // 7. Lấy danh sách sản phẩm có sẵn - ưu tiên theo ngày, fallback theo tháng
        $productQuery = StorageProduct::distinct();

        // Luôn ưu tiên filter theo ngày nếu có filterDate
        if ($filterDate) {
            $productQuery->whereDate('created_at', $filterDate);
        } elseif ($filterMonth) {
            $productQuery->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$filterMonth]);
        }

        $productIds = $productQuery->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->orderBy('name')->get();

        // 8. Lấy danh sách nhân viên có sẵn - ưu tiên theo ngày + product filter
        $employeeQuery = StorageProduct::distinct();

        // Luôn ưu tiên filter theo ngày nếu có filterDate
        if ($filterDate) {
            $employeeQuery->whereDate('created_at', $filterDate);
        } elseif ($filterMonth) {
            $employeeQuery->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$filterMonth]);
        }

        // Apply product filter for employees if selected
        if ($request->filled('product_id')) {
            $employeeQuery->where('product_id', $request->input('product_id'));
        }

        $employeeIds = $employeeQuery->pluck('employee_id');
        $employees = Employee::whereIn('id', $employeeIds)->orderBy('name')->get();

        // 9. Query dữ liệu storage cuối cùng - ưu tiên theo ngày
        $storageQuery = StorageProduct::with(['product', 'employee']);

        // Luôn ưu tiên filter theo ngày nếu có filterDate
        if ($filterDate) {
            $storageQuery->whereDate('created_at', $filterDate);
        } elseif ($filterMonth) {
            $storageQuery->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$filterMonth]);
        }

        // Apply other filters
        if ($request->filled('product_id')) {
            $storageQuery->where('product_id', $request->input('product_id'));
        }

        if ($request->filled('employee_id')) {
            $storageQuery->where('employee_id', $request->input('employee_id'));
        }

        $storage = $storageQuery->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->created_at)->format('Y-m-d').'|'.$item->employee_id.'|'.$item->product_id;
            });

        // 8. Xử lý logic lot modal (giữ nguyên như code cũ)
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
            'availableMonths',
            'availableDates',
            'filterMonth',
            'filterDate',
            'lotModalData'
        ));
    }
}
