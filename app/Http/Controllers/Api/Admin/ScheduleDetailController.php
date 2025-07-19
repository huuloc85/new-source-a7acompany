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
    public function index(Request $request)
    {
        try {
            $scheduleDetails = QueryBuilder::for(ScheduleDetail::class)
                ->allowedFilters([
                    'date',
                    'employee_id',
                    'schedule_id',
                    'is_wc_clean_men',
                    'is_wc_clean_women',
                    'is_wc_trash',
                    'is_eat_room',
                    'hnhc',
                    'employees.name',
                    'employees.id',
                    'employees.calendar_category_id',
                ])
                ->allowedFields([
                    'date',
                    'employee_id',
                    'schedule_id',
                    'is_wc_clean_men',
                    'is_wc_clean_women',
                    'is_wc_trash',
                    'is_eat_room',
                    'hnhc',
                    'created_at',
                    'updated_at',
                    'employees.name',
                    'employees.id',
                    'employees.phone',
                    'employees.calendar_category_id',
                ])
                ->defaultSort('-date')
                ->allowedSorts([
                    'date',
                    'employee_id',
                    'schedule_id',
                ])
                ->allowedIncludes([
                    'employees',
                    'schedules',
                    'employees.attendanceRecords',
                    'employees.calendarCategory',
                ]);
            $limit = $request->input('limit');
            if (! is_null($limit) && $limit == 0) {
                $limit = $scheduleDetails->count();
            }
            $scheduleDetails = $scheduleDetails->paginate($limit ?? 10);

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
