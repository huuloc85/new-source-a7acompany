<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\HandleError;
use App\Http\Controllers\Controller;
use App\Models\DailyQuantity;
use App\Models\DailyQuantityPO;
use App\Models\Product;
use App\Models\TotalDailyQuantity;
use App\Models\TotalDailyQuantityPO;
use App\Models\TotalMonthQuantity;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckPoController extends Controller
{
    public function addPoImport(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $request->validate([
                'date' => 'required|date_format:Y-m-d',
                'status' => 'required|in:1,6',
                'shift' => 'nullable|integer',
                'products' => 'required|array',
                'products.*.quantity' => 'required|integer',
                'products.*.productId' => 'required|integer|exists:products,id',
            ]);

            $date = $validate['date'];
            $month = date('m-Y', strtotime($date));
            $status = $validate['status'];
            $shift = $validate['shift'] ?? null;

            $results = collect($validate['products'])->map(function ($product) use ($date, $month, $status, $shift) {
                $productId = $product['productId'];
                $quantity = $product['quantity'];
                $created_at = Carbon::now();

                // Determine created_at if status == 1 and shift is set
                if ($status == 1 && $shift !== null) {
                    if ($shift == 1) {
                        $created_at = $date . ' 19:30:00';
                    } else {
                        $created_at = date('Y-m-d', strtotime($date . ' +1 day')) . ' 07:30:00';
                    }
                }

                // Create daily quantity
                $dailyQuan = DailyQuantity::create([
                    'product_id' => $productId,
                    'employee_id' => auth()->user()->id,
                    'quantity' => $quantity,
                    'status' => $status,
                    'date' => $date,
                    'created_at' => $created_at,
                ]);

                // Update or create total daily
                $totalDaily = TotalDailyQuantity::updateOrCreate(
                    [
                        'product_id' => $productId,
                        'date' => $date,
                        'status' => $status,
                    ],
                    [
                        'totalQuan' => DB::raw('COALESCE(totalQuan,0)+' . $quantity),
                    ]
                );

                // Update or create total month
                $totalMonth = TotalMonthQuantity::updateOrCreate(
                    [
                        'product_id' => $productId,
                        'month' => $month,
                        'status' => $status,
                    ],
                    [
                        'totalQuan' => DB::raw('COALESCE(totalQuan,0)+' . $quantity),
                    ]
                );

                return [
                    'daily' => $dailyQuan,
                    'totalDaily' => $totalDaily,
                    'totalMonth' => $totalMonth,
                ];
            });

            DB::commit();

            return response()->json([
                'message' => 'Cập nhật số lượng thành công!',
                'count' => $results->count(),
                'data' => $results,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return HandleError::handle($th);
        }
    }

    public function addPoExport(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $request->validate([
                'date' => 'required|date_format:Y-m-d',
                'fileName' => 'required|string',
                'products' => 'required|array',
                'products.*.quantity' => 'required|integer|min:1',
                'products.*.productId' => 'required|integer|exists:products,id',
            ]);

            $date = $validate['date'];
            $fileName = $validate['fileName'];
            $status = 8;
            $employeeId = Auth::id();

            // Tạo batch_id chung cho tất cả records trong request này
            $batchId = Str::uuid()->toString();

            $results = collect($validate['products'])->map(function ($product) use ($date, $status, $employeeId, $batchId, $fileName) {
                $productId = $product['productId'];
                $quantity = $product['quantity'];

                // Always create a new DailyQuantityPO record
                $dailyPo = DailyQuantityPO::create([
                    'product_id' => $productId,
                    'employee_id' => $employeeId,
                    'quantity' => $quantity,
                    'status' => $status,
                    'date' => $date,
                    'batch_id' => $batchId,
                    'file_name' => $fileName,
                ]);

                // Update or create total daily PO
                $totalDailyPO = TotalDailyQuantityPO::where('product_id', $productId)
                    ->where('date', $date)
                    ->where('status', $status)
                    ->first();

                if ($totalDailyPO) {
                    $totalDailyPO->totalQuan += $quantity;
                    $totalDailyPO->save();
                } else {
                    $totalDailyPO = TotalDailyQuantityPO::create([
                        'product_id' => $productId,
                        'date' => $date,
                        'status' => $status,
                        'totalQuan' => $quantity,
                    ]);
                }

                return [
                    'dailyPO' => $dailyPo,
                    'totalDailyPO' => $totalDailyPO,
                ];
            });

            DB::commit();

            return response()->json([
                'message' => 'Cập nhật số lượng thành công!',
                'count' => $results->count(),
                'data' => $results,
                'batch_id' => $batchId,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return HandleError::handle($th);
        }
    }

    public function addStockQuantityInventory(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $request->validate([
                'month' => 'required|date_format:m-Y',
                'products' => 'required|array',
                'products.*.productId' => 'required|integer|exists:products,id',
                'products.*.quantity' => 'required|integer',
            ]);

            $month = $validate['month'];
            $status = 4;

            $results = collect($validate['products'])->map(function ($product) use ($month, $status) {
                $productId = $product['productId'];
                $quantity = $product['quantity'];
                $productModel = Product::find($productId);
                if ($productModel) {
                    $totalMonth = TotalMonthQuantity::where('product_id', $productId)
                        ->where('month', $month)
                        ->where('status', $status)
                        ->first();
                    if ($totalMonth) {
                        $totalMonth->totalQuan = $quantity;
                        $totalMonth->save();
                    } else {
                        $totalMonth = TotalMonthQuantity::create([
                            'product_id' => $productId,
                            'month' => $month,
                            'status' => $status,
                            'totalQuan' => $quantity,
                        ]);
                    }

                    return $totalMonth;
                }

                return null;
            })->filter();

            DB::commit();

            return response()->json([
                'message' => 'Cập nhật số lượng thành công!',
                'count' => $results->count(),
                'data' => $results->values(),
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return HandleError::handle($th);
        }
    }

    public function getPoHistory(Request $request)
    {
        $month = $request->input('month') ? Carbon::createFromFormat('Y-m', $request->input('month')) : Carbon::now();

        $dailyQuantities = DailyQuantityPO::whereYear('date', $month->year)
            ->whereMonth('date', $month->month)
            ->orderBy('date', 'asc')
            ->with(['product', 'employee'])
            ->get();

        $dates = DailyQuantityPO::whereYear('date', $month->year)
            ->whereMonth('date', $month->month)
            ->select('date')
            ->distinct()
            ->orderBy('date')
            ->pluck('date');

        return response()->json([
            'month' => $month,
            'dailyQuantitiesPo' => $dailyQuantities,
            'dates' => $dates,
        ], 200);
    }

    public function updatePO(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $request->validate([
                'date' => 'required|date_format:Y-m-d',
                'products' => 'required|array',
                'products.*.quantity' => 'required|integer',
                'products.*.productId' => 'required|integer|exists:products,id',
            ]);

            $date = $validate['date'];
            $status = 8;

            $results = collect($validate['products'])->map(function ($product) use ($date, $status) {
                $productId = $product['productId'];
                $quantity = $product['quantity'];
                $employeeId = Auth::id();

                // Update or create DailyQuantityPO
                $dailyQuantity = DailyQuantityPO::updateOrCreate(
                    [
                        'product_id' => $productId,
                        'date' => $date,
                        'status' => $status,
                    ],
                    [
                        'quantity' => $quantity,
                        'employee_id' => $employeeId,
                    ]
                );

                // Update or create TotalDailyQuantityPO
                $totalDailyQuantity = TotalDailyQuantityPO::updateOrCreate(
                    [
                        'product_id' => $productId,
                        'date' => $date,
                        'status' => $status,
                    ],
                    [
                        'totalQuan' => $quantity,
                    ]
                );

                return [
                    'dailyPO' => $dailyQuantity,
                    'totalDailyPO' => $totalDailyQuantity,
                ];
            });

            DB::commit();

            return response()->json([
                'message' => 'Cập nhật số lượng thành công!',
                'count' => $results->count(),
                'data' => $results,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return HandleError::handle($th);
        }
    }

    public function deleteBatch(string $batchId)
    {
        try {
            $records = DailyQuantityPO::where('batch_id', $batchId)->get();
            $count = $records->count();

            if ($count === 0) {
                return response()->json(['message' => 'Batch không tồn tại'], 404);
            }

            // Collect affected dates for recalculating totals
            $affectedDates = $records->pluck('date')->unique();

            // Delete all records in the batch
            DailyQuantityPO::where('batch_id', $batchId)->delete();

            // Update TotalDailyQuantityPO for each affected date
            foreach ($affectedDates as $date) {
                $totalDailyQuantities = TotalDailyQuantityPO::where('date', $date)->get();
                foreach ($totalDailyQuantities as $totalDailyQuantity) {
                    $remainingQuantity = DailyQuantityPO::where('product_id', $totalDailyQuantity->product_id)
                        ->whereDate('date', $date)
                        ->sum('quantity');
                    $totalDailyQuantity->totalQuan = $remainingQuantity;
                    $totalDailyQuantity->save();
                }
            }

            return response()->json([
                'message' => "Đã xóa {$count} records trong batch",
                'deleted_count' => $count,
            ]);
        } catch (\Throwable $th) {
            return HandleError::handle($th);
        }
    }

    public function deletePO($id)
    {
        try {
            // Find and get the record in DailyQuantityPO to delete
            $dailyQuantity = DailyQuantityPO::findOrFail($id);
            $date = $dailyQuantity->date;

            // Delete the record in DailyQuantityPO
            $dailyQuantity->delete();

            // Update the records in TotalDailyQuantityPo for the specified date
            $totalDailyQuantities = TotalDailyQuantityPO::where('date', $date)->get();
            foreach ($totalDailyQuantities as $totalDailyQuantity) {
                $remainingDailyQuantities = DailyQuantityPO::where('product_id', $totalDailyQuantity->product_id)
                    ->whereDate('date', $date)
                    ->get();
                $totalQuantity = $remainingDailyQuantities->sum('quantity');
                $totalDailyQuantity->totalQuan = $totalQuantity;
                $totalDailyQuantity->save();
            }

            return response()->json([
                'message' => 'Đã xoá thành công sản lượng PO và cập nhật lại tổng sản lượng.',
                'success' => true,
            ], 200);
        } catch (\Throwable $th) {
            return HandleError::handle($th);
        }
    }
}
