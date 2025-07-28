<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\HandleError;
use App\Models\SendStamp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;
use Spatie\QueryBuilder\QueryBuilder;

class StampController extends BaseController
{
    public function savePrint(Request $request)
    {
        try {
            DB::beginTransaction();
            Log::info('Request to save print received', ['request' => $request->all()]);

            $validation = $request->validate([
                'productId' => 'required|exists:products,id',
                'type' => 'required|string|in:box,bag,Tem Thùng,Tem Bịch',
                'date' => 'required|date_format:Y-m-d',
                'shift' => 'required|in:1,2',
                'binCount' => 'required|integer|min:1',
                'binStart' => 'required|regex:/^[0-9]+(,[0-9]+)*$/',
                'employee_id' => 'sometimes|exists:employees,id',
                'stamp_id' => 'sometimes|exists:send_stamps,id',
            ]);

            if ($request['stamp_id']) {
                $originalStamp = SendStamp::findOrFail($validation['stamp_id']);
                $originalStamp->update([
                    'status' => 'approve',
                    'manager_id' => Auth()->user()->id,
                    'manager_time' => Carbon::now()->format('H:i:s'),
                ]);
                Log::info('Stamp updated successfully', ['stamp_id' => $originalStamp->id]);
                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Stamp updated successfully',
                    'data' => $originalStamp,
                ], 200);
            }

            $binList = explode(',', $validation['binStart']);
            foreach ($binList as $bin) {
                SendStamp::updateOrCreate([
                    'product_id' => $validation['productId'],
                    'manager_id' => Auth()->user()->id,
                    'employee_id' => $validation['employee_id'] ?? Auth()->user()->id,
                    'type' => $validation['type'],
                    'date' => $validation['date'],
                    'shift' => $validation['shift'] ?? 1,
                    'binCount' => count($binList) > 1 ? 1 : $validation['binCount'],
                    'binStart' => count($binList) > 1 ? $bin : $validation['binStart'],
                    'manager_time' => Carbon::now()->format('H:i:s'),
                    'status' => 'approve',
                ]);
            }

            Log::info('Print saved successfully');
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Print saved successfully',
                'data' => [
                    'product_id' => $validation['productId'],
                    'type' => $validation['type'],
                    'date' => $validation['date'],
                    'shift' => $validation['shift'],
                    'binCount' => $validation['binCount'],
                    'binStart' => count($binList) > 1 ? $binList : $validation['binStart'],
                ],
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            return HandleError::handle($e);
        }
    }

    public function getStampHistory(Request $request)
    {
        try {
            $stampHistory = QueryBuilder::for(SendStamp::class)
                ->allowedFilters([
                    'employee_id',
                    'manager_id',
                    'date',
                    'shift',
                    'status',
                    'binCount',
                    'binStart',
                    'type',
                    'product.name',
                    'employee.name',
                    'created_at',
                ])
                ->allowedSorts(['created_at'])
                ->defaultSort('-created_at')
                ->allowedIncludes(['product', 'employee', 'manager'])
                ->paginate($request->get('limit', 10));

            return response()->json($stampHistory, 200);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    public function rejectPrint($id)
    {
        DB::beginTransaction();
        try {
            $stamp = SendStamp::findOrFail($id);
            $stamp->update([
                'status' => 'rejected',
                'manager_id' => auth()->id(),
                'manager_time' => Carbon::now()->format('H:i:s'),
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Print request rejected successfully',
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();

            return HandleError::handle($e);
        }
    }
}
