<?php

namespace App\Http\Controllers\Api;

use App\Helpers\HandleError;
use App\Models\AttendanceRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class AttendanceController extends BaseController
{
    public function history(Request $request)
    {
        try {

            $this->validate($request, [
                'month' => 'date_format:Y-m',
            ]);

            $currentMonth = $request->input('month', Carbon::now()->format('Y-m'));

            $records = QueryBuilder::for(AttendanceRecord::class)
                ->allowedFilters([
                    'date',
                    'time',
                    'employee.code',
                    'employee.name',
                    'employee.category_celender_id',
                ])
                ->defaultSort('-datetime')
                ->allowedSorts([
                    'date',
                    'employee.category_celender_id',
                    'employee.code',
                    'employee.name',
                ])
                ->whereYear('date', Carbon::parse($currentMonth)->year)
                ->whereMonth('date', Carbon::parse($currentMonth)->month)
                ->with(['employee'])
                ->paginate($request->input('limit'));

            return response()->json($records);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }

    }
}
