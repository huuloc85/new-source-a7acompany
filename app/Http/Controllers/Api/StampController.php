<?php

namespace App\Http\Controllers\Api;

use App\Helpers\HandleError;
use App\Models\SendStamp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Log;

class StampController extends BaseController
{
    public function savePrint(Request $request)
    {
        try {
            Log::info('Request to save print received', ['request' => $request->all()]);

            $validation = $request->validate([
                'productId' => 'required|exists:products,id',
                'type' => 'required|string|in:box,bag,Tem Thùng,Tem Bịch',
                'date' => 'required|date_format:Y-m-d',
                'shift' => 'required|in:1,2',
                'binCount' => 'required|integer|min:1',
                'binStart' => 'required|integer|min:1',
            ]);

            $history = SendStamp::create([
                'product_id' => $validation['productId'],
                'manager_id' => Auth()->user()->id,
                'employee_id' => Auth()->user()->id,
                'type' => $validation['type'] ?? 'box',
                'date' => $validation['date'] ?? Carbon::now()->format('Y-m-d'),
                'shift' => $validation['shift'] ?? 1,
                'binCount' => $validation['binCount'] ?? 1,
                'binStart' => $validation['binStart'] ?? 1,
                'manager_time' => Carbon::now()->format('H:i:s'),
                'status' => 'approve',
            ]);

            Log::info('Print saved successfully', ['history' => $history]);

            return response()->json([
                'status' => 'success',
                'message' => 'Print saved successfully',
                'data' => $history,
            ], 201);

        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }

    }
}
