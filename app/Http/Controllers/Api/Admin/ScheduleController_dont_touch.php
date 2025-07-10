<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\HandleError;
use App\Helpers\LogActivity;
use App\Helpers\LogHelper;
use App\Imports\Celender\CelenderManagerImport;
use App\Models\CategoryCelender;
use App\Models\Celender;
use App\Models\CelenderDetailEatroom;
use App\Models\CelenderDetailHNHC;
use App\Models\CelenderDetailWC;
use App\Models\CelenderDetailWCCleanMen;
use App\Models\CelenderDetailWCCleanWomen;
use App\Models\Employee;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ScheduleController_dont_touch extends BaseController
{
    public function index(Request $request)
    {
        try {
            $schedules = QueryBuilder::for(Schedule::class)
                ->allowedFields([
                    'id', 'title', 'date', 'created_at', 'updated_at',
                    'employees.id', 'employees.code', 'employees.name', 'employees.category_celender_id',
                ])
                ->allowedFilters([
                    'title', 'date', AllowedFilter::scope('date_between'),
                    'employees.code', 'employees.name',
                ])
                ->defaultSort('-date')
                ->allowedSorts([
                    'title', 'date', 'created_at', 'updated_at',
                    'employees.code', 'employees.name',
                ])
                ->allowedIncludes(['employees', 'scheduleDetails'])
                ->paginate($request->input('limit'));

            return response()->json($schedules, 200);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $celender = Celender::find($id);
            if (! $celender) {
                return response()->json(['status' => false, 'message' => 'Không tìm thấy lịch làm việc'], 404);
            }
            if (! empty($request->employee_id)) {
                foreach ($request->employee_id as $employee_id) {
                    foreach ([
                        'hnhc' => CelenderDetailHNHC::class,
                        'eatroom' => CelenderDetailEatroom::class,
                        'wc' => CelenderDetailWC::class,
                        'wccleanwomen' => CelenderDetailWCCleanWomen::class,
                        'wccleanmen' => CelenderDetailWCCleanMen::class,
                    ] as $suffix => $model) {
                        $key = $employee_id.'-'.$suffix;
                        if (! empty($request->$key)) {
                            $detail = $model::where('celender_id', $id)->where('employee_id', $employee_id)->first();
                            if ($detail) {
                                foreach ($request->$key as $i => $celenderVal) {
                                    $detail->{'day'.($i + 1)} = $celenderVal;
                                }
                                $detail->save();
                            }
                        }
                    }
                }
            }
            DB::commit();

            return response()->json(['status' => true, 'message' => 'Cập nhật lịch làm việc thành công!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Errors: '.$e->getMessage().' getLine: '.$e->getLine());

            return response()->json([
                'status' => false,
                'message' => 'Cập nhật lịch làm việc không thành công!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            if (CategoryCelender::count() <= 0) {
                return response()->json(['status' => false, 'message' => 'Bạn phải thêm danh mục lịch làm việc trước khi thêm lịch làm việc!'], 422);
            }
            $employees = Employee::whereNotIn('role_id', [15, 16, 17])->get();
            if ($employees->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'Bạn phải thêm nhân sự trước khi thêm lịch làm việc!'], 422);
            }
            $celender = Celender::create(['title' => $request->title, 'date' => $request->date]);
            if ($request->file('fileImport')) {
                $excelFile = $request->file('fileImport')->store('temp');
                Excel::import(new CelenderManagerImport($celender->id), $excelFile);
            }
            DB::commit();
            LogActivity::logRoleSpecificLoginActivity(auth()->user(), 'Admin Thêm Lịch Làm Việc', 'Admin đã thêm lịch làm việc');

            return response()->json(['status' => true, 'message' => 'Thêm lịch làm việc mới thành công!', 'data' => $celender], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            LogHelper::saveLog('Import-Celender', $e->getMessage(), $e->getLine());
            Log::error('Errors: '.$e->getMessage().' getLine: '.$e->getLine());

            return response()->json(['status' => false, 'message' => 'Thêm lịch làm việc mới không thành công!', 'error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $celender = Celender::find($id);
            if (! $celender) {
                return response()->json(['status' => false, 'message' => 'Không tìm thấy lịch làm việc'], 404);
            }
            foreach ([
                CelenderDetailHNHC::class,
                CelenderDetailEatroom::class,
                CelenderDetailWC::class,
                CelenderDetailWCCleanWomen::class,
                CelenderDetailWCCleanMen::class,
            ] as $model) {
                $model::where('celender_id', $id)->delete();
            }
            $celender->delete();
            LogActivity::logRoleSpecificLoginActivity(auth()->user(), 'Admin Xoá Lịch Làm Việc', 'Admin đã xoá lịch làm việc');
            DB::commit();

            return response()->json(['status' => true, 'message' => 'Xoá lịch làm việc thành công!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Errors: '.$e->getMessage().' getLine: '.$e->getLine());

            return response()->json(['status' => false, 'message' => 'Xoá lịch làm việc không thành công!', 'error' => $e->getMessage()], 500);
        }
    }

    public function detail($id)
    {
        try {
            $schedule = Celender::select('id', 'title', 'date')->find($id);
            if (! $schedule) {
                return response()->json(['status' => false, 'message' => 'Không tìm thấy lịch làm việc'], 404);
            }
            $withEmployee = fn ($q) => $q->whereNull('deleted_at');
            $getWith = fn ($model, $order = null) => $model::where('celender_id', $id)
                ->whereHas('employee', $withEmployee)
                ->with('employee')
                ->when($order, fn ($q) => $q->orderBy($order))
                ->get();
            $categories = CategoryCelender::all(['id', 'name']);

            return response()->json([
                'schedule' => $schedule,
                'scheduleDetailsHNHC' => $getWith(CelenderDetailHNHC::class),
                'scheduleDetailsEatRoom' => $getWith(CelenderDetailEatroom::class, 'celender_detail_eatroom.id'),
                'scheduleDetailsWC' => $getWith(CelenderDetailWC::class),
                'scheduleDetailsWCCleanWomen' => $getWith(CelenderDetailWCCleanWomen::class),
                'scheduleDetailsWCCleanMen' => $getWith(CelenderDetailWCCleanMen::class),
                'categories' => $categories,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Errors: '.$e->getMessage().' getLine: '.$e->getLine());

            return response()->json([
                'status' => false,
                'message' => 'Lấy chi tiết lịch làm việc không thành công!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
