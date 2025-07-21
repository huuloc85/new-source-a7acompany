<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\HandleError;
use App\Models\CelenderDetailHNHC;
use App\Models\CheckEmployee;
use App\Models\Product;
use App\Models\TotalMonthQuantity;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends BaseController
{
    public function getMonthList()
    {
        $months = TotalMonthQuantity::distinct()->pluck('month');
        $sortedMonths = $months->sortByDesc(function ($month) {
            return Carbon::createFromFormat('m-Y', $month);
        })->values();

        return response()->json([
            'months' => $sortedMonths,
        ]);
    }

    public function getProducts(Request $request)
    {
        $requestHash = md5(json_encode($request->all()));
        $key = 'products:list:'.$requestHash;

        // return Cache::tags(['products'])->remember($key, 3600, function () use ($request) {
        try {
            $validated = $request->validate([
                'limit' => 'nullable|integer|min:0',
                'page' => 'nullable|integer|min:1',
                'month' => 'nullable|date_format:Y-m',
                'status' => 'nullable|integer|min:1|max:7',
            ]);

            $currentMonth = $validated['month'] ?? null;
            $status = $validated['status'] ?? null;

            $products = QueryBuilder::for(Product::class)
                ->allowedFields(
                    'id',
                    'code',
                    'name',
                    'moldSize',
                    'CAV',
                    'cycle',
                    'binCode',
                    'quanEntityBin',
                    'FAPV',
                    'FASV',
                    'FAVV',
                )
                ->allowedSorts([
                    'id',
                    'code',
                    'name',
                    'moldSize',
                    'binCode',
                    'created_at',
                    'updated_at',
                ])
                ->allowedFilters([
                    'id',
                    'code',
                    'name',
                    'moldSize',
                    'binCode',
                    'created_at',
                    'updated_at',
                ])
                ->allowedIncludes([
                    AllowedInclude::callback('totalmonthquantities', function ($query) use ($currentMonth, $status) {
                        if ($currentMonth) {
                            $query->where('month', Carbon::parse($currentMonth)->format('m-Y'));
                        }
                        if ($status) {
                            $query->where('status', $status);
                        }
                    }),
                    AllowedInclude::callback('totaldailyquantities', function ($query) use ($currentMonth, $status) {
                        if ($currentMonth) {
                            $query->where('date', 'like', $currentMonth.'-%');
                        }
                        if ($status) {
                            $query->where('status', $status);
                        }
                    }),
                    AllowedInclude::callback('dailyquantities', function ($query) use ($currentMonth, $status) {
                        if ($currentMonth) {
                            $query->where('date', 'like', $currentMonth.'-%');
                        }
                        if ($status) {
                            $query->where('status', $status);
                        }
                    }),
                    AllowedInclude::callback('totaldailyquantitiespo', function ($query) use ($currentMonth) {
                        if ($currentMonth) {
                            $query->where('date', 'like', $currentMonth.'-%');
                        }
                    }),
                ]);

            // Logic to get products
            $limit = $validated['limit'] ?? 10;
            if (! is_null($limit) && $limit == 0) {
                $limit = $products->count();
            }
            $products = $products->paginate($limit);

            return response()->json($products);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
        // });
    }

    public function getProduct($id)
    {
        return Cache::tags(['products'])->remember('products:detail:'.$id, 3600, function () use ($id) {
            try {
                $product = Product::findOrFail($id);

                $companies = [];
                if ($product->FAPV) {
                    array_push($companies, 'FAPV');
                }
                if ($product->FASV) {
                    array_push($companies, 'FASV');
                }
                if ($product->FAVV) {
                    array_push($companies, 'FAVV');
                }
                $product->companies = $companies;

                return response()->json($product);
            } catch (\Throwable $e) {
                return HandleError::handle($e);
            }
        });
    }

    public function addProduct(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'code' => 'required|string|unique:products,code',
                'name' => 'required|string',
                'stockQuanMOQ' => 'required|numeric',
                'stockQuan' => 'required|numeric',
                'moldSize' => 'required|string',
                'CAV' => 'required|numeric',
                'cycle' => 'required|numeric',
                'stockQuan200' => 'required|numeric',
                'binCode' => 'required|string',
                'quanEntityBin' => 'required|numeric',
                'companies' => 'required|array',
            ]);

            $product = Product::create([
                ...$validated,
                'FAPV' => in_array('FAPV', $validated['companies']) ? 1 : 0,
                'FASV' => in_array('FASV', $validated['companies']) ? 1 : 0,
                'FAVV' => in_array('FAVV', $validated['companies']) ? 1 : 0,
            ]);

            $month = Carbon::now()->format('m-Y');

            // Create a new TotalMonthQuantity record
            $stockQuan = TotalMonthQuantity::create([
                'product_id' => $product->id,
                'month' => $month,
                'status' => 4,
                'totalQuan' => $validated['stockQuan'],

            ]);

            $stockQuan200 = TotalMonthQuantity::create([
                'product_id' => $product->id,
                'month' => $month,
                'status' => 5,
                'totalQuan' => $validated['stockQuan200'],
            ]);

            $stockQuanMOQ = TotalMonthQuantity::create([
                'product_id' => $product->id,
                'month' => $month,
                'status' => 7,
                'totalQuan' => $validated['stockQuanMOQ'],
            ]);

            Cache::tags(['products'])->flush();
            Cache::tags(['total-month-quantity'])->flush();
            DB::commit();

            return response()->json([
                'message' => 'Product created successfully',
                'data' => $product,
                'stockQuantity' => $stockQuan,
                'stockQuantity200' => $stockQuan200,
                'stockQuantityMOQ' => $stockQuanMOQ,
                'month' => $month,
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            return HandleError::handle($e);
        }
        // Logic to create a new product
    }

    public function updateProduct(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);

            $validated = $request->validate([
                'code' => 'sometimes|string|unique:products,code,'.$id,
                'name' => 'sometimes|string',
                'moldSize' => 'sometimes|string',
                'CAV' => 'sometimes|numeric',
                'cycle' => 'sometimes|numeric',
                'binCode' => 'sometimes|string',
                'quanEntityBin' => 'sometimes|numeric',
                'companies' => 'sometimes|array',
            ]);

            $product->update([
                ...$validated,
                'FAPV' => in_array('FAPV', $validated['companies']) ? 1 : 0,
                'FASV' => in_array('FASV', $validated['companies']) ? 1 : 0,
                'FAVV' => in_array('FAVV', $validated['companies']) ? 1 : 0,
            ]);

            Cache::tags(['products'])->flush();
            DB::commit();

            return response()->json([
                'message' => 'Product updated successfully',
                'data' => $product,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return HandleError::handle($e);
        }
    }

    public function deleteProduct($id)
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            Cache::tags(['products'])->flush();
            DB::commit();

            return response()->json([
                'message' => 'Product deleted successfully',
                'data' => $product,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return HandleError::handle($e);
        }
    }

    public function getTrashProducts(Request $request)
    {
        $requestHash = md5(json_encode($request->all()));
        $key = 'products:trash:'.$requestHash;

        return Cache::tags(['products'])->remember($key, 3600, function () use ($request) {
            try {
                $validated = $request->validate([
                    'limit' => 'integer|nullable',
                    'page' => 'integer|nullable',
                ]);

                $products = QueryBuilder::for(Product::class)
                    ->onlyTrashed()
                    ->allowedSorts([
                        'id',
                        'code',
                        'name',
                        'moldSize',
                        'CAV',
                        'binCode',
                        'quanEntityBin',
                        'created_at',
                        'updated_at',
                    ])
                    ->allowedFilters([
                        'id',
                        'code',
                        'name',
                        'moldSize',
                        'binCode',
                        'created_at',
                        'updated_at',
                    ]);

                $limit = $validated['limit'] ?? 10;
                if (! is_null($limit) && $limit == 0) {
                    $limit = $products->count();
                }
                $products = $products->paginate($limit);

                return response()->json($products);
            } catch (\Throwable $e) {
                return HandleError::handle($e);
            }
        });
    }

    public function restoreProduct($id)
    {
        DB::beginTransaction();
        try {
            $product = Product::onlyTrashed()->findOrFail($id);
            $product->restore();

            Cache::tags(['products'])->flush();
            DB::commit();

            return response()->json([
                'message' => 'Product restored successfully',
                'data' => $product,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return HandleError::handle($e);
        }
    }

    public function updateQuantity()
    {
        try {
            $userId = auth()->user()->id;

            $calendar = CelenderDetailHNHC::where('employee_id', $userId)->latest()->first();
            if (! $calendar) {
                return response()->json([
                    'message' => 'Không tìm thấy lịch làm việc cho nhân viên này.',
                ], 404);
            }

            $date = Carbon::now()->format('d');
            $date = $this->convertDate($date); // Chuyển đổi nếu cần
            $column = 'day'.$date;

            if (! isset($calendar->$column)) {
                return response()->json([
                    'message' => "Không có dữ liệu lịch làm việc cho cột: $column.",
                ], 404);
            }

            $calendarDetail = $this->translateCalendar($calendar->$column);

            $today = Carbon::now()->toDateString();
            $yesterday = Carbon::now()->subDay()->toDateString();

            $addQuantity = CheckEmployee::where('employee_id', $userId)
                ->where(function ($query) use ($today, $yesterday) {
                    $query->whereDate('date', $today)
                        ->orWhere(function ($query) use ($yesterday) {
                            $query->whereDate('date', $yesterday)
                                ->where('shift', 'Ca 2');
                        });
                })
                ->whereHas('product', function ($query) {
                    $query->whereColumn('products.id', 'check_employees.product_id');
                })
                ->get()
                ->filter(function ($item) use ($today, $yesterday) {
                    $itemDate = Carbon::parse($item->date)->toDateString();

                    return $itemDate === $today || ($itemDate === $yesterday && $item->shift === 'Ca 2');
                })
                ->values(); // Reset chỉ số mảng

            return response()->json([
                'calendar_detail' => $calendarDetail,
                'quantities' => $addQuantity,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Hãy bổ sung lịch làm việc để cập nhật sản lượng!',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
