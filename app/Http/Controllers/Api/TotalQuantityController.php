<?php

namespace App\Http\Controllers\Api;

use App\Helpers\HandleError;
use App\Models\DailyQuantity;
use App\Models\TotalDailyQuantity;
use App\Models\TotalMonthQuantity;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\QueryBuilder;

class TotalQuantityController extends BaseController
{
    public function getMonthly(Request $request)
    {
        $requestHash = md5(json_encode($request->all()));
        $key = 'total-month-quantity:list:'.$requestHash;

        return Cache::tags('total-month-quantity')->remember($key, 3600, function () use ($request) {
            try {
                $totalMonthlyQuantities = QueryBuilder::for(TotalMonthQuantity::class)
                    ->allowedSorts([
                        'id',
                        'product_id',
                        'quantity',
                        'month',
                        'status',
                    ])
                    ->allowedFilters([
                        'id',
                        'product_id',
                        'quantity',
                        'month',
                        'status',
                    ]);

                if (! is_null($request['month'])) {
                    $totalMonthlyQuantities->where('month', $request['month']);
                }

                if ($request['status']) {
                    $totalMonthlyQuantities->where('status', $request['status']);
                }

                if ($request['productId']) {
                    $totalMonthlyQuantities->where('product_id', $request['productId']);
                }

                $limit = $request->limit;
                if (! is_null($limit) && $limit == 0) {
                    $limit = $totalMonthlyQuantities->count();
                }
                $totalMonthlyQuantities = $totalMonthlyQuantities->paginate($limit ?? 10);

                return response()->json($totalMonthlyQuantities);
            } catch (\Throwable $e) {
                return HandleError::handle($e);
            }
        });
    }

    public function updateMonthQuantity(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $request->validate([
                'month' => 'required|string',
                'status' => 'required|numeric|min:0|max:8',
                'productId' => 'required|integer|exists:products,id',
                'quantity' => 'required|integer',
            ]);

            $totalQuantity = TotalMonthQuantity::updateOrCreate(
                [
                    'product_id' => $validate['productId'],
                    'month' => $validate['month'],
                    'status' => $validate['status'],
                ],
                [
                    'totalQuan' => $validate['quantity'],
                ]
            );
            Cache::tags('total-month-quantity')->flush();
            DB::commit();

            return response()->json($totalQuantity);
        } catch (\Throwable $e) {
            DB::rollBack();

            return HandleError::handle($e);
        }
    }

    public function updateMonthQuantities(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $request->validate([
                'month' => 'required|string',
                'status' => 'required|numeric|min:0|max:8',
                'products' => 'nullable|array',
                'products.*.quantity' => 'required|integer',
                'products.*.productId' => 'required|integer|exists:products,id',
            ]);

            $totalQuantities = collect($validate['products'])->map(function ($product) use ($validate) {
                return TotalMonthQuantity::updateOrCreate(
                    [
                        'product_id' => $product['productId'],
                        'month' => $validate['month'],
                        'status' => $validate['status'],
                    ],
                    [
                        'totalQuan' => $product['quantity'],
                    ]
                );
            });
            Cache::tags(['products', 'total-month-quantity'])->flush();
            DB::commit();

            return response()->json([
                'message' => 'Total quantities updated successfully.',
                'count' => $totalQuantities->count(),
                'data' => $totalQuantities,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return HandleError::handle($e);
        }
    }

    public function addProductsQuantity(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $request->validate([
                'products' => 'required|array',
                'products.*.date' => 'required|string|date_format:d-m-Y',
                'products.*.status' => 'required|numeric|min:0|max:8',
                'products.*.shift' => 'nullable|integer|in:1,2',
                'products.*.quantity' => 'required|integer',
                'products.*.productId' => 'required|exists:products,id',
            ]);

            $totalQuantities = collect($validate['products'])->map(function ($product) {
                $date = date('Y-m-d', strtotime($product['date']));
                $status = $product['status'];
                $shift = $product['shift'] ?? null;
                $quantity = $product['quantity'];
                $productId = $product['productId'];
                $createdAt = null;

                if ($status == 1 && ! is_null($shift)) {
                    if ($shift == 1) {
                        $createdAt = $date.'19:30:00 ';
                    } else {
                        $createdAt = date('Y-m-d', strtotime($date.' +1 day')).'07:30:00 ';
                    }
                } else {
                    $createdAt = Carbon::now();
                }

                // Create the daily quantity record
                $dayQuantity = new DailyQuantity;
                $dayQuantity->product_id = $productId;
                $dayQuantity->employee_id = Auth()->user()->id;
                $dayQuantity->quantity = $quantity;
                $dayQuantity->date = $date;
                $dayQuantity->status = $status;
                $dayQuantity->created_at = $createdAt;
                $dayQuantity->save();

                // Create or update the total daily and monthly quantities
                $totalDaily = TotalDailyQuantity::firstOrNew(
                    [
                        'product_id' => $productId,
                        'date' => $date,
                        'status' => $status,
                    ]
                );
                if ($totalDaily->exists) {
                    $totalDaily->totalQuan += $quantity;
                } else {
                    $totalDaily->totalQuan = $quantity;
                }
                $totalDaily->save();

                $totalMonth = TotalMonthQuantity::firstOrNew(
                    [
                        'product_id' => $productId,
                        'month' => date('m-Y', strtotime($date)),
                        'status' => $status,
                    ]
                );
                if ($totalMonth->exists) {
                    $totalMonth->totalQuan += $quantity;
                } else {
                    $totalMonth->totalQuan = $quantity;
                }
                $totalMonth->save();

                return [
                    'daily' => $dayQuantity,
                    'totalDaily' => $totalDaily,
                    'totalMonth' => $totalMonth,
                ];
            });

            Cache::tags(['products', 'total-month-quantity'])->flush();
            DB::commit();

            return response()->json([
                'message' => 'Total quantities added successfully.',
                'count' => $totalQuantities->count(),
                'data' => $totalQuantities,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return HandleError::handle($e);
        }
    }
}
