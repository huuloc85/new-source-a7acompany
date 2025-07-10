<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\HandleError;
use App\Models\ScheduleDetail;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ScheduleDetailController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $scheduleDetails = QueryBuilder::for(ScheduleDetail::class)
                ->allowedFilters([
                    'date',
                    'employee_id',
                    'schedule_id',
                ])
                ->defaultSort('-date')
                ->allowedSorts([
                    'date',
                    'employee_id',
                    'schedule_id',
                ])
                ->allowedIncludes([
                    'employee',
                    'schedule',
                    'employee.attendanceRecords',
                ])
                ->paginate(request()->input('limit'));

            return response()->json($scheduleDetails);
        } catch (\Throwable $th) {
            return HandleError::handle($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
