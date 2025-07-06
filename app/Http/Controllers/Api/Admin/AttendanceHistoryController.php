<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\HandleError;
use App\Models\AttendanceRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AttendanceHistoryController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $requestHash = md5(json_encode($request->all()));
            $key = 'attendances:history:'.$requestHash;

            return Cache::tags(['attendances'])->remember($key, 3600, function () use ($request) {
                $records = QueryBuilder::for(AttendanceRecord::class)
                    ->allowedFilters([
                        'datetime',
                        'date',
                        'time',
                        'created_at',
                        'updated_at',
                        'employee.code',
                        'employee.name',
                        'employee.category_celender_id',
                        AllowedFilter::scope('date_between'),
                        AllowedFilter::scope('time_between'),
                        AllowedFilter::scope('datetime_between'),
                    ])
                    ->defaultSort('-datetime')
                    ->allowedSorts([
                        'datetime',
                        'date',
                        'time',
                        'created_at',
                        'updated_at',
                        'employee.code',
                        'employee.name',
                        'employee.category_celender_id',
                    ]);

                $records = $records->with(['employee'])
                    ->paginate($request->input('limit'));

                return response()->json($records);
            });
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    public function show($id)
    {
        try {
            $key = 'attendances:history:show:'.$id;

            return Cache::tags(['attendances'])->remember($key, 3600, function () use ($id) {
                $record = AttendanceRecord::with(['employee'])->findOrFail($id);

                return response()->json($record);
            });
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'employee_code' => 'required|exists:employees,code',
                'datetime' => 'required|date_format:Y-m-d H:i:s',
            ]);

            $request->merge([
                'date' => Carbon::parse($request->input('datetime'))->format('Y-m-d'),
                'time' => Carbon::parse($request->input('datetime'))->format('H:i:s'),
            ]);

            $record = AttendanceRecord::create([
                'employee_code' => $request->input('employee_code'),
                'datetime' => $request->input('datetime'),
                'date' => $request->input('date'),
                'time' => $request->input('time'),
            ]);

            Cache::tags(['attendances'])->flush();
            DB::commit();

            return response()->json([
                'message' => 'created successfully',
                'data' => $record,
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            return HandleError::handle($e);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $record = AttendanceRecord::findOrFail($id);
            $this->validate($request, [
                'employee_code' => 'sometimes|exists:employees,code',
                'datetime' => 'required|date_format:Y-m-d H:i:s',
                'date' => 'sometimes|date',
                'time' => 'sometimes|date_format:H:i:s',
            ]);
            $record->update($request->all());

            Cache::tags(['attendances'])->flush();
            DB::commit();

            return response()->json([
                'message' => 'updated successfully',
                'data' => $record,
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();

            return HandleError::handle($e);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $record = AttendanceRecord::findOrFail($id);
            $record->delete();

            Cache::tags(['attendances'])->flush();
            DB::commit();

            return response()->json([
                'message' => 'Cache cleared successfully',
                'data' => $record,
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();

            return HandleError::handle($e);
        }
    }
}
