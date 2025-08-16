<?php

namespace App\Http\Controllers\api\employee;

use App\Events\StampNotificationEvent;
use App\Helpers\HandleError;
use App\Http\Controllers\Controller;
use App\Models\SendStamp;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class EmpStampController extends Controller
{
    public function requestStamp(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $validate = $request->validate([
                'stamps' => 'required|array',
                'stamps.*.type' => 'required|in:box,bag',
                'stamps.*.productId' => 'required|exists:products,id',
                'stamps.*.date' => 'required|date_format:Y-m-d',
                'stamps.*.shift' => 'required|in:1,2',
                'stamps.*.binCount' => 'required|integer|min:1',
                'stamps.*.binStart' => 'required|integer|min:1',
            ]);
            $stamps = [];
            foreach ($validate['stamps'] as $stamp) {
                $stamps[] = SendStamp::create([
                    'employee_id' => $user->id,
                    'type' => $stamp['type'],
                    'product_id' => $stamp['productId'],
                    'date' => Carbon::parse($stamp['date'])->toDateString(),
                    'shift' => $stamp['shift'],
                    'binCount' => $stamp['binCount'],
                    'binStart' => $stamp['binStart'],
                    'status' => 'pending',
                ]);
            }

            $roles = [15, 8, 21];

            foreach ($stamps as $stamp) {
                foreach ($roles as $role) {
                    event(new StampNotificationEvent($stamp, $role));
                }
            }
            DB::commit();

            return response()->json(
                [
                    'message' => 'Stamp request created successfully',
                    'data' => [
                        'employee_id' => $user->id,
                        'stamps' => $validate['stamps'],
                    ],
                ],
                201
            );
        } catch (\Throwable $th) {
            DB::rollBack();

            return HandleError::handle($th);
        }
    }

    public function getStampHistory(Request $request)
    {
        try {
            $user = Auth::user();
            $stampHistory = QueryBuilder::for(SendStamp::class)
                ->where('employee_id', $user->id)
                ->allowedFilters([
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
                ->allowedIncludes(['product', 'employee', 'manager']);

            $limit = $request->get('limit', 10);
            if ($limit == 0) {
                $limit = $stampHistory->count();
            }

            $stampHistory = $stampHistory->paginate($limit);

            return response()->json($stampHistory, 200);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }
}
