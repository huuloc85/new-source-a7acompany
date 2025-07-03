<?php

namespace App\Http\Controllers\Api;

use App\Helpers\HandleError;
use App\Models\SendStamp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

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
            ]);

            $binList = explode(',', $validation['binStart']);
            foreach ($binList as $bin) {
                SendStamp::create([
                    'product_id' => $validation['productId'],
                    'manager_id' => Auth()->user()->id,
                    'employee_id' => Auth()->user()->id,
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
}
