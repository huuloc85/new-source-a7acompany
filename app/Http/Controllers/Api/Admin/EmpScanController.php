<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\HandleError;
use App\Models\Employee;
use App\Models\Product;
use App\Models\StorageProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmpScanController extends BaseController
{
    public function checkBarCode(Request $request)
    {
        DB::beginTransaction();
        try {
            $employee = Auth::user();
            $employeeId = $employee->id;

            $validate = $request->validate([
                'barcode' => 'required|string',
            ]);

            // Tách barcode với cả 'a' thường và 'A' hoa
            $barcode = preg_split('/[aA]/', $validate['barcode'], 2);

            // Kiểm tra định dạng barcode hợp lệ
            if (count($barcode) !== 2 || empty($barcode[0]) || empty($barcode[1])) {
                return response()->json([
                    'error' => [
                        'code' => 400,
                        'message' => 'Bad Request',
                        'errors' => [
                            'barcode' => ['Định dạng barcode không hợp lệ.'],
                        ],
                    ],
                ], 400);
            }

            $productId = $barcode[0];
            $barcodeData = $barcode[1];

            // Kiểm tra độ dài barcode data tối thiểu (8 ngày + 1 ca + ít nhất 1 số thùng)
            if (strlen($barcodeData) < 10) {
                return response()->json([
                    'error' => [
                        'code' => 400,
                        'message' => 'Bad Request',
                        'errors' => [
                            'barcode' => ['Dữ liệu barcode không đủ dài.'],
                        ],
                    ],
                ], 400);
            }

            $date = substr($barcodeData, 0, 8);
            $shift = substr($barcodeData, 8, 1);
            $bin = substr($barcodeData, 9);

            // Validate ngày có đúng định dạng ddmmyyyy không
            if (! preg_match('/^\d{8}$/', $date)) {
                return response()->json([
                    'error' => [
                        'code' => 400,
                        'message' => 'Bad Request',
                        'errors' => [
                            'barcode' => ['Định dạng ngày trong barcode không hợp lệ (phải là ddmmyyyy).'],
                        ],
                    ],
                ], 400);
            }

            // Validate ca làm việc (1 hoặc 2)
            if (! in_array($shift, ['1', '2'])) {
                return response()->json([
                    'error' => [
                        'code' => 400,
                        'message' => 'Bad Request',
                        'errors' => [
                            'barcode' => ['Ca làm việc phải là 1 hoặc 2.'],
                        ],
                    ],
                ], 400);
            }

            // Kiểm tra số thùng không được rỗng và phải là số
            if (empty($bin) || ! is_numeric($bin)) {
                return response()->json([
                    'error' => [
                        'code' => 400,
                        'message' => 'Bad Request',
                        'errors' => [
                            'barcode' => ['Số thùng không được để trống và phải là số.'],
                        ],
                    ],
                ], 400);
            }

            // Đảm bảo số thùng có định dạng 3 chữ số (pad với 0 ở đầu nếu cần)
            $bin = str_pad($bin, 3, '0', STR_PAD_LEFT);
            $lot = 'A-'.$date.'-'.$shift.'-'.$bin;

            $storage = StorageProduct::where('product_id', $productId)
                ->where('lot', $lot)
                ->first();
            // If the lot already exists, return an error
            if ($storage) {
                return response()->json([
                    'error' => [
                        'code' => 409,
                        'message' => 'Conflict',
                        'errors' => [
                            'lot' => ['The lot has already been taken.'],
                        ],
                    ],
                ], 409);
            }

            $storageProduct = StorageProduct::create([
                'product_id' => $productId,
                'lot' => $lot,
                'employee_id' => $employeeId,
                'bin' => $bin,
            ]);

            DB::commit();

            return response()->json($storageProduct, 201);
        } catch (\Throwable $th) {
            DB::rollBack();

            return HandleError::handle($th);
        }
    }

    /**
     * Handles the retrieval and filtering of storage product data for the admin API.
     *
     * This method provides a comprehensive API endpoint for querying storage product records,
     * supporting filtering by date, month, product, and employee. It also returns available
     * filter options for months, dates, products, and employees, as well as detailed lot
     * information if requested.
     *
     * Workflow:
     * 1. Determines the latest available date in the storage product data to use as the default filter.
     * 2. Applies date and month filters based on request input, defaulting to the latest date/month if not provided.
     * 3. Retrieves all available months and dates for filtering.
     * 4. Validates the selected filter date against available dates.
     * 5. Retrieves available products and employees based on the current filters.
     * 6. Queries the main storage product data, applying all relevant filters.
     * 7. If lot and lot_product_id are provided, processes and validates the lot code,
     *    checks for missing lots, and returns detailed lot modal data.
     *
     * Returns a JSON response containing:
     * - Filtered storage product data, grouped by date, employee, and product.
     * - Lists of available products and employees for the current filters.
     * - Lists of available months and dates for filtering.
     * - The currently selected filter month and date.
     * - Detailed lot modal data if requested, including missing lots and status.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming HTTP request containing filter parameters.
     *                                             - filter_date: (optional) The specific date to filter storage products.
     *                                             - filter_month: (optional) The specific month to filter storage products.
     *                                             - product_id: (optional) Filter by product ID.
     *                                             - employee_id: (optional) Filter by employee ID.
     *                                             - lot: (optional) Lot code for lot modal data.
     *                                             - lot_product_id: (optional) Product ID for lot modal data.
     * @return \Illuminate\Http\JsonResponse JSON response with storage data, filter options, and lot modal data.
     */
    public function StorageProduct(Request $request)
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
            $lotCode = preg_replace_callback('/^A-(\d{8})-(\d+)-(\d+)$/', function ($matches) {
                return "A-{$matches[1]}-{$matches[2]}-".str_pad($matches[3], 3, '0', STR_PAD_LEFT);
            }, $lotCode);

            $productId = (int) $request->input('lot_product_id');

            if (! preg_match('/^A-(\d{2})(\d{2})(\d{4})-([12])-(\d+)$/', $lotCode, $matches)) {
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

                        // Fix: Convert missing numbers to array and reset keys
                        $missingLots = array_values(array_map(
                            fn ($num) => $lotPrefix.str_pad($num, 3, '0', STR_PAD_LEFT),
                            $missingNumbers
                        ));

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

        // Return as JSON for API
        return response()->json([
            'storage' => $storage,
            'products' => $products,
            'employees' => $employees,
            'availableMonths' => $availableMonths,
            'availableDates' => $availableDates,
            'filterMonth' => $filterMonth,
            'filterDate' => $filterDate,
            'lotModalData' => $lotModalData,
        ]);
    }
}
