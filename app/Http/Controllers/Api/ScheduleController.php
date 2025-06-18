<?php

namespace App\Http\Controllers\Api;

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
use App\Utils\SearchFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ScheduleController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $schedules = Celender::query()->select('id', 'title', 'date');

            SearchFilter::apply(
                $schedules,
                $request,
                [
                    'title' => 'string',
                    'date' => 'date',
                ],
                [
                    'title',
                    'date',
                ]
            );

            $limit = $request->limit;
            if (! is_null($limit) && $limit == 0) {
                $limit = $schedules->count();
            }
            $schedules = $schedules->paginate($limit ?? 10);

            return response()->json($schedules, 200);
        } catch (\Exception $e) {
            Log::error('Error getting schedules: '.$e->getMessage().' at line '.$e->getLine());

            return response()->json([
                'error' => [
                    'code' => 500,
                    'message' => 'An error occurred while fetching schedules.',
                ],
            ], 500);
        }
    }

    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $countCategoryCelender = CategoryCelender::count();

            if ($countCategoryCelender <= 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Bạn phải thêm danh mục lịch làm việc trước khi thêm lịch làm việc!',
                ], 422);
            }

            $employees = Employee::where('role_id', '!=', 15)
                ->where('role_id', '!=', 16)
                ->where('role_id', '!=', 17)
                ->get();

            if (count($employees) <= 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Bạn phải thêm nhân sự trước khi thêm lịch làm việc!',
                ], 422);
            }

            $celender = new Celender;
            $celender->title = $request->title;
            $celender->date = $request->date;
            $celender->save();

            $excelFile = null;
            if ($request->file('fileImport')) {
                $excelFile = $request->file('fileImport')->store('temp');
            }

            if ($excelFile != null && $celender != null) {
                Excel::import(new CelenderManagerImport($celender->id), $excelFile);
            }

            DB::commit();
            LogActivity::logRoleSpecificLoginActivity(auth()->user(), 'Admin Thêm Lịch Làm Việc', 'Admin đã thêm lịch làm việc');

            return response()->json([
                'status' => true,
                'message' => 'Thêm lịch làm việc mới thành công!',
                'data' => $celender,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            LogHelper::saveLog('Import-Celender', $e->getMessage(), $e->getLine());
            Log::error('Errors: '.$e->getMessage().' getLine: '.$e->getLine());

            return response()->json([
                'status' => false,
                'message' => 'Thêm lịch làm việc mới không thành công!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $celender = Celender::find($id);

            if (! $celender) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không tìm thấy lịch làm việc',
                ], 404);
            }

            if (! empty($request->employee_id) && count($request->employee_id) != 0) {
                foreach ($request->employee_id as $employee_id) {
                    // HNHC
                    $key = $employee_id.'-hnhc';
                    if ($request->$key && count($request->$key) != 0) {
                        $celenderDetailHNHC = CelenderDetailHNHC::where('celender_id', $id)
                            ->where('employee_id', $employee_id)
                            ->first();

                        if ($celenderDetailHNHC) {
                            foreach ($request->$key as $keyCelender => $celender) {
                                $fillName = 'day'.($keyCelender + 1);
                                $celenderDetailHNHC->$fillName = $celender;
                            }
                            $celenderDetailHNHC->save();
                        }
                    }

                    // Eatroom
                    $key = $employee_id.'-eatroom';
                    if ($request->$key && count($request->$key) != 0) {
                        $celenderDetailEatroom = CelenderDetailEatroom::where('celender_id', $id)
                            ->where('employee_id', $employee_id)
                            ->first();

                        if ($celenderDetailEatroom) {
                            foreach ($request->$key as $keyCelender => $celender) {
                                $fillName = 'day'.($keyCelender + 1);
                                $celenderDetailEatroom->$fillName = $celender;
                            }
                            $celenderDetailEatroom->save();
                        }
                    }

                    // WC vứt rác
                    $key = $employee_id.'-wc';
                    if ($request->$key && count($request->$key) != 0) {
                        $celenderDetailWC = CelenderDetailWC::where('celender_id', $id)
                            ->where('employee_id', $employee_id)
                            ->first();

                        if ($celenderDetailWC) {
                            foreach ($request->$key as $keyCelender => $celender) {
                                $fillName = 'day'.($keyCelender + 1);
                                $celenderDetailWC->$fillName = $celender;
                            }
                            $celenderDetailWC->save();
                        }
                    }

                    // WC trực nữ
                    $key = $employee_id.'-wccleanwomen';
                    if ($request->$key && count($request->$key) != 0) {
                        $celenderDetailWCCleanWomen = CelenderDetailWCCleanWomen::where('celender_id', $id)
                            ->where('employee_id', $employee_id)
                            ->first();

                        if ($celenderDetailWCCleanWomen) {
                            foreach ($request->$key as $keyCelender => $celender) {
                                $fillName = 'day'.($keyCelender + 1);
                                $celenderDetailWCCleanWomen->$fillName = $celender;
                            }
                            $celenderDetailWCCleanWomen->save();
                        }
                    }

                    // WC trực nam
                    $key = $employee_id.'-wccleanmen';
                    if ($request->$key && count($request->$key) != 0) {
                        $celenderDetailWCCleanMen = CelenderDetailWCCleanMen::where('celender_id', $id)
                            ->where('employee_id', $employee_id)
                            ->first();

                        if ($celenderDetailWCCleanMen) {
                            foreach ($request->$key as $keyCelender => $celender) {
                                $fillName = 'day'.($keyCelender + 1);
                                $celenderDetailWCCleanMen->$fillName = $celender;
                            }
                            $celenderDetailWCCleanMen->save();
                        }
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Cập nhật lịch làm việc thành công!',
            ], 200);
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

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $celender = Celender::find($id);

            if (! $celender) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không tìm thấy lịch làm việc',
                ], 404);
            }

            CelenderDetailHNHC::where('celender_id', $id)->delete();
            CelenderDetailEatroom::where('celender_id', $id)->delete();
            CelenderDetailWC::where('celender_id', $id)->delete();
            CelenderDetailWCCleanWomen::where('celender_id', $id)->delete();
            CelenderDetailWCCleanMen::where('celender_id', $id)->delete();
            $celender->delete();

            LogActivity::logRoleSpecificLoginActivity(auth()->user(), 'Admin Xoá Lịch Làm Việc', 'Admin đã xoá lịch làm việc');

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Xoá lịch làm việc thành công!',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Errors: '.$e->getMessage().' getLine: '.$e->getLine());

            return response()->json([
                'status' => false,
                'message' => 'Xoá lịch làm việc không thành công!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function detail($id)
    {
        try {
            $schedule = Celender::select('id', 'title', 'date')->find($id);

            if (! $schedule) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không tìm thấy lịch làm việc',
                ], 404);
            }

            $scheduleDetailsHNHC = CelenderDetailHNHC::where('celender_id', $id)
                ->whereHas('employee', function ($query) {
                    $query->where('deleted_at', '=', null);
                })
                ->with('employee')
                ->get();

            $scheduleDetailsEatRoom = CelenderDetailEatroom::where('celender_id', $id)
                ->whereHas('employee', function ($query) {
                    $query->where('deleted_at', '=', null);
                })
                ->with('employee')
                ->orderBy('celender_detail_eatroom.id')
                ->get();

            $scheduleDetailsWC = CelenderDetailWC::where('celender_id', $id)
                ->whereHas('employee', function ($query) {
                    $query->where('deleted_at', '=', null);
                })
                ->with('employee')
                ->get();

            $scheduleDetailsWCCleanWomen = CelenderDetailWCCleanWomen::where('celender_id', $id)
                ->whereHas('employee', function ($query) {
                    $query->where('deleted_at', '=', null);
                })
                ->with('employee')
                ->get();

            $scheduleDetailsWCCleanMen = CelenderDetailWCCleanMen::where('celender_id', $id)
                ->whereHas('employee', function ($query) {
                    $query->where('deleted_at', '=', null);
                })
                ->with('employee')
                ->get();

            $categories = CategoryCelender::all()->select('id', 'name');

            return response()->json(
                [
                    'schedule' => $schedule,
                    'scheduleDetailsHNHC' => $scheduleDetailsHNHC,
                    'scheduleDetailsEatRoom' => $scheduleDetailsEatRoom,
                    'scheduleDetailsWC' => $scheduleDetailsWC,
                    'scheduleDetailsWCCleanWomen' => $scheduleDetailsWCCleanWomen,
                    'scheduleDetailsWCCleanMen' => $scheduleDetailsWCCleanMen,
                    'categories' => $categories,
                ],
                200
            );
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
