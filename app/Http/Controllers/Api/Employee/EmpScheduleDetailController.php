<?php

namespace App\Http\Controllers\Api\Employee;

use App\Helpers\HandleError;
use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Models\ScheduleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EmpScheduleDetailController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::user()->id;
        try {
            $scheduleDetails = QueryBuilder::for(ScheduleDetail::class)
                ->where('employee_id', $userId)
                ->allowedFilters([
                    'date',
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

            LogActivity::logViewActivity(auth()->user(), 'Xem Lịch Làm Việc', 'Nhân viên xem danh sách lịch làm việc');

            return response()->json($scheduleDetails);
        } catch (\Throwable $th) {
            return HandleError::handle($th);
        }
    }

    public function show(Request $request, $id)
    {
        $userId = Auth::user()->id;
        try {
            $scheduleDetails = QueryBuilder::for(ScheduleDetail::class)
                ->where('employee_id', $userId)
                ->where('schedule_id', $id)
                ->allowedFilters([
                    'date',
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

    public function showByDateRange(Request $request)
    {
        $userId = Auth::user()->id;
        try {
            $scheduleDetails = QueryBuilder::for(ScheduleDetail::class)
                ->where('employee_id', $userId)
                ->allowedFilters([
                    'date',
                    'schedule_id',
                    'is_wc_clean_men',
                    'is_wc_clean_women',
                    'is_wc_trash',
                    'is_eat_room',
                    'hnhc',
                    AllowedFilter::scope('date_between'),
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
}
