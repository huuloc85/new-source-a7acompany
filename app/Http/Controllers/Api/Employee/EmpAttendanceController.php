<?php

namespace App\Http\Controllers\Api\Employee;

use App\Helpers\HandleError;
use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\ScheduleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EmpAttendanceController extends Controller
{
    public function index(Request $request)
    {
        try {
            $requestHash = md5(json_encode($request->all()));
            $key = 'attendances:employee:detail'.$requestHash;

            return Cache::tags(['attendances'])->remember($key, 3600, function () use ($request) {
                $user = Auth::user();
                $query = ScheduleDetail::query()
                    ->where('employee_id', $user->id)
                    ->leftJoin('attendance_records', function ($join) {
                        $join->on('schedule_details.date', '=', 'attendance_records.date')
                            ->on('schedule_details.employee_id', '=', 'attendance_records.employee_code');
                    })
                    ->rightJoin('employees', function ($join) {
                        $join->on('schedule_details.employee_id', '=', 'employees.id')
                            ->whereNull('employees.deleted_at');
                    })
                    ->leftJoin('calendar_categories', function ($join) {
                        $join->on('employees.calendar_category_id', '=', 'calendar_categories.id');
                    })
                    ->select([
                        'schedule_details.employee_id',
                        'employees.name',
                        'schedule_details.schedule_id',
                        'employees.calendar_category_id',
                        'calendar_categories.name as calendar_category_name',
                        'schedule_details.is_wc_clean_men',
                        'schedule_details.is_wc_clean_women',
                        'schedule_details.is_wc_trash',
                        'schedule_details.is_eat_room',
                        'schedule_details.hnhc',
                        'schedule_details.date',
                        'attendance_records.datetime',
                        'attendance_records.time',
                        'employees.company',
                    ]);

                $arrayDate = explode(',', $request->input('filter.date_between'));

                if (count($arrayDate) !== 2) {
                    $query->whereBetween('schedule_details.date', [
                        Carbon::now()->startOfMonth(),
                        Carbon::now(),
                    ]);
                }

                $records = QueryBuilder::for($query)
                    ->allowedFilters([
                        'employee_id',
                        'date',
                        'employees.name',
                        'employees.company',
                        AllowedFilter::callback('date_between', function ($query, $value) {
                            if (is_array($value) && count($value) === 2) {
                                $start = Carbon::parse($value[0])->subDay();
                                $end = Carbon::parse($value[1]) > Carbon::now() ? Carbon::now()->addDay() : Carbon::parse($value[1])->addDay();
                                $query->whereBetween('schedule_details.date', [
                                    $start,
                                    $end,
                                ]);
                            }
                        }),
                        'employees.calendar_category_id',
                    ])
                    ->defaultSort('date')
                    ->allowedSorts([
                        'employee_id',
                        'name',
                        'date',
                        'calendar_category_id',
                        'calendar_category_name',
                    ])
                    ->get();

                $grouped = $records->groupBy(['employee_id', 'name', 'date']);
                $result = [];
                foreach ($grouped as $employee_id => $byName) {
                    foreach ($byName as $name => $byDate) {
                        $datesList = $byDate->keys()->sort()->values();
                        foreach ($byDate as $date => $items) {
                            $dates = $items->filter(function ($item) {
                                return ! empty($item->datetime);
                            })->map(function ($item) {
                                return [
                                    'datetime' => $item->datetime,
                                    'date' => $item->date,
                                    'time' => $item->time,
                                ];
                            })->values();

                            $currentIndex = $datesList->search($date);
                            $yesterday = $currentIndex !== false && $currentIndex > 0 ? $byDate[$datesList[$currentIndex - 1]] : [];
                            $tomorrow = $currentIndex !== false && $currentIndex < $datesList->count() - 1 ? $byDate[$datesList[$currentIndex + 1]] : [];

                            $result[] = [
                                'employee_id' => $employee_id,
                                'name' => $name,
                                'date' => $date,
                                'calendar_category_id' => $items->first()->calendar_category_id,
                                'calendar_category_name' => $items->first()->calendar_category_name,
                                'schedule_id' => $items->first()->schedule_id,
                                'is_wc_clean_men' => $items->first()->is_wc_clean_men,
                                'is_wc_clean_women' => $items->first()->is_wc_clean_women,
                                'is_wc_trash' => $items->first()->is_wc_trash,
                                'is_eat_room' => $items->first()->is_eat_room,
                                'hnhc' => $items->first()->hnhc,
                                'company' => $items->first()->company,
                                'dates' => (function () use ($yesterday, $dates, $tomorrow, $date) {
                                    $result = $dates->toArray();

                                    if ($yesterday && $yesterday->isNotEmpty()) {
                                        $prevDate = Carbon::parse($date)->copy()->subDay()->format('Y-m-d');
                                        if ($yesterday->first()->date === $prevDate) {
                                            $result = array_merge(
                                                $yesterday->filter(function ($item) {
                                                    return ! empty($item->datetime);
                                                })->map(function ($item) {
                                                    return [
                                                        'datetime' => $item->datetime,
                                                        'date' => $item->date,
                                                        'time' => $item->time,
                                                    ];
                                                })->toArray(),
                                                $result
                                            );
                                        }
                                    }

                                    if ($tomorrow && $tomorrow->isNotEmpty()) {
                                        $nextDate = Carbon::parse($date)->copy()->addDay()->format('Y-m-d');
                                        if ($tomorrow->first()->date === $nextDate) {
                                            $result = array_merge(
                                                $result,
                                                $tomorrow->filter(function ($item) {
                                                    return ! empty($item->datetime);
                                                })->map(function ($item) {
                                                    return [
                                                        'datetime' => $item->datetime,
                                                        'date' => $item->date,
                                                        'time' => $item->time,
                                                    ];
                                                })->toArray()
                                            );
                                        }
                                    }

                                    return $result;
                                })(),
                            ];
                        }
                    }
                }

                if ($arrayDate && count($arrayDate) === 2) {
                    $start = Carbon::parse($arrayDate[0])->startOfDay();
                    $end = Carbon::parse($arrayDate[1])->endOfDay();
                    $result = array_filter($result, function ($item) use ($start, $end) {
                        $date = Carbon::parse($item['date']);

                        return $date->between($start, $end) || $date->equalTo($start) || $date->equalTo($end);
                    });
                }

                $page = request()->input('page', 1);
                $perPage = request()->input('limit', 15);
                if ($perPage == 0) {
                    $perPage = max(1, count($result));
                }
                $offset = ($page - 1) * $perPage;
                $paginated = array_slice($result, $offset, $perPage);
                $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                    $paginated,
                    count($result),
                    $perPage,
                    $page,
                    ['path' => request()->url(), 'query' => request()->query()]
                );

                LogActivity::logViewActivity(auth()->user(), 'Xem Lịch Sử Chấm Công', 'Nhân viên xem danh sách chấm công');

                return response()->json($paginator);
            });
        } catch (\Throwable $th) {
            return HandleError::handle($th);
        }
    }

    public function show($id)
    {
        try {
            $key = 'attendances:history:show:'.$id;

            return Cache::tags(['attendances'])->remember($key, 3600, function () use ($id) {
                $record = AttendanceRecord::with(['employees'])->findOrFail($id);

                LogActivity::logViewActivity(auth()->user(), 'Xem Chi Tiết Chấm Công', 'Nhân viên xem chi tiết bản ghi chấm công');

                return response()->json($record);
            });
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $record = AttendanceRecord::findOrFail($id);
            $this->validate($request, [
                'employee_code' => 'sometimes|exists:employees,id',
                'datetime' => 'required|date_format:Y-m-d H:i:s',
                'date' => 'sometimes|date',
                'time' => 'sometimes|date_format:H:i:s',
            ]);
            $record->update($request->all());

            Cache::tags(['attendances'])->flush();
            DB::commit();

            LogActivity::logViewActivity(auth()->user(), 'Cập Nhật Chấm Công', 'Nhân viên cập nhật thông tin chấm công');

            return response()->json([
                'message' => 'updated successfully',
                'data' => $record,
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();

            return HandleError::handle($e);
        }
    }

    public function history(Request $request)
    {
        try {
            $requestHash = md5(json_encode($request->all()));
            $key = 'attendances:employee:history'.$requestHash;

            return Cache::tags(['attendances'])->remember($key, 3600, function () use ($request) {
                $user = Auth::user();
                $records = QueryBuilder::for(AttendanceRecord::class)
                    ->where('employee_code', $user->id)
                    ->allowedFilters([
                        'datetime',
                        'date',
                        'time',
                        'created_at',
                        'updated_at',
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
                    ])
                    ->paginate($request->input('limit'));

                LogActivity::logViewActivity(auth()->user(), 'Xem Lịch Sử Chấm Công', 'Nhân viên xem lịch sử chấm công cá nhân');

                return response()->json($records);
            });
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }
}
