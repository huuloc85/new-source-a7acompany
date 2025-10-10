<?php

namespace App\Http\Controllers\Api\Employee;

use App\Events\StampNotificationEvent;
use App\Helpers\HandleError;
use App\Helpers\LogActivity;
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
                'stamps.*.binStart' => 'required|string',
                'stamps.*.purpose' => 'nullable|in:new,additional,reprint',
            ]);
            $stamps = [];
            foreach ($validate['stamps'] as $stamp) {
                // Validate và chuẩn hóa binStart
                $this->validateBinStart($stamp['binStart']);

                $stamps[] = SendStamp::create([
                    'employee_id' => $user->id,
                    'type' => $stamp['type'],
                    'product_id' => $stamp['productId'],
                    'date' => Carbon::parse($stamp['date'])->toDateString(),
                    'shift' => $stamp['shift'],
                    'binCount' => $stamp['binCount'] ?? 1,
                    'binStart' => $stamp['binStart'],
                    'status' => 'pending',
                    'purpose' => $stamp['purpose'] ?? null,
                ]);
            }

            $roles = [8, 21, 13];

            foreach ($stamps as $stamp) {
                foreach ($roles as $role) {
                    event(new StampNotificationEvent($stamp, $role));
                }
            }
            DB::commit();

            LogActivity::logViewActivity(auth()->user(), 'Yêu Cầu In Tem', 'Nhân viên gửi yêu cầu in tem');

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

            LogActivity::logViewActivity(auth()->user(), 'Xem Lịch Sử Yêu Cầu In Tem', 'Nhân viên xem lịch sử yêu cầu in tem');

            return response()->json($stampHistory, 200);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    /**
     * Validate binStart string - có thể là một số hoặc nhiều số cách nhau bởi dấu phẩy
     */
    private function validateBinStart(string $binStart): void
    {
        $numbers = array_map('trim', explode(',', $binStart));

        foreach ($numbers as $number) {
            if (! is_numeric($number) || (int) $number < 1) {
                throw new \InvalidArgumentException("Invalid binStart value: {$number}");
            }
        }
    }
}
