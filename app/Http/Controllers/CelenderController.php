<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Helpers\LogActivity;
use App\Http\Requests\CelenderStoreRequest;
use App\Imports\Celender\CelenderManagerImport;
use App\Models\CategoryCelender;
use App\Models\Celender;
use App\Models\CelenderDetailEatroom;
use App\Models\CelenderDetailHNHC;
use App\Models\CelenderDetailWC;
use App\Models\CelenderDetailWCClean;
use App\Models\CelenderDetailWCCleanMen;
use App\Models\CelenderDetailWCCleanWomen;
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class CelenderController extends Controller
{
    // view list
    public function index(Request $request)
    {
        $celenders = Celender::query();
        if (!empty($request->key)) {
            $celenders->Name($request);
        }
        $total = count($celenders->get());
        $celenders = $celenders->orderBy('id', 'DESC')->paginate(Celender::paginate);
        return view('celender.index', compact('celenders', 'total'));
    }

    //view add
    public function add(CelenderStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $countCategoryCelender = CategoryCelender::count();
            if ($countCategoryCelender > 0) {
                $employees = Employee::where('role_id', '!=', 15)
                    ->where('role_id', '!=', 16)
                    ->where('role_id', '!=', 17)
                    ->get();
                if (count($employees) > 0) {
                    $celender = new Celender();
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

                    toast('Thêm lịch làm việc mới thành công!', 'success', 'top-right');
                    LogActivity::logRoleSpecificLoginActivity(auth()->user(), 'Admin Thêm Lịch Làm Việc', 'Admin đã thêm lịch làm việc');
                    return redirect()->route('admin.celender.home');
                } else {
                    $roles = Role::where('id', '!=', 15)
                        ->where('id', '!=', 16)
                        ->where('id', '!=', 17)->get();
                    $categories = CategoryCelender::all();
                    toast('Bạn phải thêm nhân sự trước khi thêm lịch làm việc!', 'success', 'top-right');
                    return view('employee.add', compact('roles', 'categories'));
                }
            } else {
                toast('Bạn phải thêm danh mục lịch làm việc trước khi thêm lịch làm việc!', 'success', 'top-right');
                return view('category.add');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            LogHelper::saveLog('Import-Celender', $e->getMessage(), $e->getLine());
            Log::error('errors' . $e->getMessage() . ' getLine' . $e->getLine());
            toast('Thêm lịch làm việc mới không thành công!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    //store new
    public function store(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            if (!empty($request->employee_id) && count($request->employee_id) != 0) {
                foreach ($request->employee_id as $employee_id) {

                    ///hnhc
                    $key = $employee_id . '-hnhc';
                    if ($request->$key  && count($request->$key) != 0) {
                        $celenderDetailHNHC = CelenderDetailHNHC::where('celender_id', $id)->where('employee_id', $employee_id)->first();
                        foreach ($request->$key as $keyCelender => $celender) {
                            $fillName = "day" . $keyCelender + 1;
                            $celenderDetailHNHC->$fillName = $celender;
                        }
                        $celenderDetailHNHC->save();
                    }

                    //eatroom
                    $key = $employee_id . '-eatroom';
                    if ($request->$key  && count($request->$key) != 0) {
                        $celenderDetailEatroom = CelenderDetailEatroom::where('celender_id', $id)->where('employee_id', $employee_id)->first();
                        foreach ($request->$key as $keyCelender => $celender) {
                            $fillName = "day" . $keyCelender + 1;
                            $celenderDetailEatroom->$fillName = $celender;
                        }
                        $celenderDetailEatroom->save();
                    }

                    //wc vứt rác
                    $key = $employee_id . '-wc';
                    if ($request->$key  && count($request->$key) != 0) {
                        $celenderDetailWC = CelenderDetailWC::where('celender_id', $id)->where('employee_id', $employee_id)->first();
                        foreach ($request->$key as $keyCelender => $celender) {
                            $fillName = "day" . $keyCelender + 1;
                            $celenderDetailWC->$fillName = $celender;
                        }
                        $celenderDetailWC->save();
                    }

                    //wc trực nữ
                    $key = $employee_id . '-wccleanwomen';
                    if ($request->$key  && count($request->$key) != 0) {
                        $celenderDetailWCCleanWomen = CelenderDetailWCCleanWomen::where('celender_id', $id)->where('employee_id', $employee_id)->first();
                        foreach ($request->$key as $keyCelender => $celender) {
                            $fillName = "day" . $keyCelender + 1;
                            $celenderDetailWCCleanWomen->$fillName = $celender;
                        }
                        $celenderDetailWCCleanWomen->save();
                    }

                    //wc trực name
                    $key = $employee_id . '-wccleanmen';
                    if ($request->$key  && count($request->$key) != 0) {
                        $celenderDetailWCCleanMen = CelenderDetailWCCleanMen::where('celender_id', $id)->where('employee_id', $employee_id)->first();
                        foreach ($request->$key as $keyCelender => $celender) {
                            $fillName = "day" . $keyCelender + 1;
                            $celenderDetailWCCleanMen->$fillName = $celender;
                        }
                        $celenderDetailWCCleanMen->save();
                    }
                }
            }
            DB::commit();
            toast('Cập nhật lịch làm việc mới thành công!', 'success', 'top-right');
            return redirect()->route('admin.celender.home');
        } catch (\Exception $th) {
            DB::rollBack();
            Log::error('errors' . $th->getMessage() . ' getLine' . $th->getLine());
            toast('Cập nhật lịch làm việc mới không thành công!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    //delete
    public function delete($id)
    {
        try {
            CelenderDetailHNHC::where('celender_id', $id)->delete();
            CelenderDetailEatroom::where('celender_id', $id)->delete();
            CelenderDetailWC::where('celender_id', $id)->delete();
            CelenderDetailWCCleanWomen::where('celender_id', $id)->delete();
            CelenderDetailWCCleanMen::where('celender_id', $id)->delete();
            Celender::where('id', $id)->delete();
            toast('Xoá lịch làm việc thành công!', 'success', 'top-right');
            LogActivity::logRoleSpecificLoginActivity(auth()->user(), 'Admin Xoá Lịch Làm Việc', 'Admin đã xoá lịch làm việc');
            return redirect()->route('admin.celender.home');
        } catch (\Exception $th) {
            Log::error('errors' . $th->getMessage() . ' getLine' . $th->getLine());
            toast('Xoá lịch làm việc không thành công!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    //detail
    public function detail(Request $request, $id)
    {
        $startDate = Celender::where('id', $id)->pluck('date')->first();
        $dates = [];
        $month = date("m", strtotime($startDate));
        $monthNext = $month;
        $date = $startDate;
        while ($monthNext == $month) {
            array_push($dates, $date);
            $date = date('Y/m/d', strtotime('+1 day', strtotime($date)));
            $monthNext = date("m", strtotime($date));
        }
        $formatDate = new Celender();
        //list cate
        $categories = CategoryCelender::all();
        //list celender detail
        $celenderDetailsHNHC = CelenderDetailHNHC::where('celender_id', $id)->whereHas('employee', function ($query) {
            $query->where('deleted_at', '=', null);
        })->get();
        $celenderDetailsEatroom = CelenderDetailEatroom::where('celender_id', $id)
            ->whereHas('employee', function ($query) {
                $query->where('deleted_at', '=', null);
            })
            ->orderBy('celender_detail_eatroom.id')
            ->get();

        $celenderDetailsWC = CelenderDetailWC::where('celender_id', $id)->whereHas('employee', function ($query) {
            $query->where('deleted_at', '=', null);
        })->get();
        $celenderDetailsWCCleanWomen = CelenderDetailWCCleanWomen::where('celender_id', $id)->whereHas('employee', function ($query) {
            $query->where('deleted_at', '=', null);
        })->get();
        $celenderDetailsWCCleanMen = CelenderDetailWCCleanMen::where('celender_id', $id)->whereHas('employee', function ($query) {
            $query->where('deleted_at', '=', null);
        })->get();
        $roles = Role::where('id', '!=', 15)
            ->where('id', '!=', 16)
            ->where('id', '!=', 17)->get();
        return view('celender.show-detail', compact(
            'dates',
            'formatDate',
            'id',
            'categories',
            'celenderDetailsHNHC',
            'celenderDetailsEatroom',
            'celenderDetailsWC',
            'celenderDetailsWCCleanWomen',
            'celenderDetailsWCCleanMen',
            'roles'
        ));
    }
}
