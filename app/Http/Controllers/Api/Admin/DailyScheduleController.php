<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\HandleError;
use App\Models\CheckEmployee;
use App\Models\DailyQuantity;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class DailyScheduleController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $allowedStatuses = [1, 2, 6];
            $filterDate = $request->input('filter.date', Carbon::today()->format('Y-m-d'));

            $employeeQuery = QueryBuilder::for(CheckEmployee::with(['employee', 'product']))
                ->whereDate('date', $filterDate)
                ->when(! empty($allowedStatuses), function ($query) use ($allowedStatuses) {
                    return $query->whereIn('status', $allowedStatuses);
                })
                ->allowedFilters(['employee_id', 'date'])
                ->allowedSorts(['employee_id', 'date']);

            $limit = $request->input('limit', 10);
            if (! is_null($limit) && $limit == 0) {
                $limit = $employeeQuery->count();
            }
            $employees = $employeeQuery->paginate($limit);

            collect($employees->items())->each(function ($checkEmployee) use ($allowedStatuses) {
                $checkEmployee->date = Carbon::parse($checkEmployee->date);
                $dailyQuantities = DailyQuantity::where('employee_id', $checkEmployee->employee_id)
                    ->where('product_id', $checkEmployee->product_id)
                    ->whereDate('date', $checkEmployee->date->format('Y-m-d'))
                    ->whereIn('status', $allowedStatuses)
                    ->get();

                $dailyQuantities->each(function ($dailyQuantity) {
                    $dailyQuantity->created_at_formatted = Carbon::parse($dailyQuantity->created_at)->format('H:i:s');
                });

                $checkEmployee->dailyQuantities = $dailyQuantities;
            });

            return response()->json($employees, 200);
        } catch (\Throwable $th) {
            return HandleError::handle($th);
        }
    }

    public function show($id)
    {
        try {
            $checkEmployee = CheckEmployee::with(['employee', 'product'])->findOrFail($id);

            return response()->json($checkEmployee, 200);
        } catch (\Throwable $th) {
            return HandleError::handle($th);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'status' => 'nullable|in:1,2,6',
            ]);

            $checkEmployee = CheckEmployee::findOrFail($id);
            $checkEmployee->update($request->only('product_id', 'status'));

            Cache::tags(['daily-schedule'])->flush();
            DB::commit();

            return response()->json(['message' => 'Updated successfully', 'data' => $checkEmployee], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return HandleError::handle($th);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $checkEmployee = CheckEmployee::findOrFail($id);
            $checkEmployee->delete();

            Cache::tags(['daily-schedule'])->flush();
            DB::commit();

            return response()->json([
                'message' => 'Deleted successfully',
                'data' => $checkEmployee,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return HandleError::handle($th);
        }
    }
}
