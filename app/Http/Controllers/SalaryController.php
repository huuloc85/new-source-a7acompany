<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Helpers\LogActivity;
use App\Http\Requests\ImportSalaryRequest;
use App\Imports\A7A\SalaryOfficialA7AManagerImport;
use App\Imports\parttime\SalaryParttimeManagerImport;
use App\Imports\VVP\SalaryOfficialVVPManagerImport;
use App\Models\SalaryManager;
use App\Models\SalaryOfficialA7A;
use App\Models\SalaryOfficialA7ATimekeeping;
use App\Models\SalaryOfficialVVP;
use App\Models\SalaryOfficialVVPTimekeeping;
use App\Models\SalaryParttime;
use App\Models\SalaryParttimeTimekeeping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $salaryManagers = SalaryManager::query();
        if (!empty($request->key)) {
            $salaryManagers->Name($request);
        }
        $total = count($salaryManagers->get());
        $salaryManagers = $salaryManagers->orderBy('id', 'DESC')->paginate(SalaryManager::paginate);
        return view('salary.index', compact('salaryManagers', 'total'));
    }

    public function getImportSalary()
    {
        return view('salary.import');
    }

    public function importSalary(ImportSalaryRequest $request)
    {
        try {
            DB::beginTransaction();
            $salaryManager = new SalaryManager();
            $salaryManager->title = $request->title;
            $salaryManager->start_date = $request->start_date;
            $salaryManager->end_date = $request->end_date;
            $startDateParttime = $request->start_date_parttime;
            $endDateParttime = $request->end_date_parttime;
            $end_date = Carbon::createFromFormat('Y-m-d', $request->end_date);
            $end_date = $end_date->addMonth()->startOfMonth();
            $salaryManager->date_show = $end_date->format('Y-m-d');
            $salaryManager->save();
            $excelFileVPP = null;
            $excelFileA7A = null;
            $excelFileParttime = null;
            if ($request->file('file_vvp')) {
                $excelFileVPP = $request->file('file_vvp')->store('temp');
            }
            if ($request->file('file_a7a')) {
                $excelFileA7A = $request->file('file_a7a')->store('temp');
            }
            // if ($request->file('file_parttime')) {
            //     $excelFileParttime = $request->file('file_parttime')->store('temp');
            // }
            if ($excelFileVPP != null && $salaryManager != null) {
                Excel::import(new SalaryOfficialVVPManagerImport($salaryManager->id, $salaryManager->start_date, $salaryManager->end_date), $excelFileVPP);
            }
            if ($excelFileA7A != null && $salaryManager != null) {
                Excel::import(new SalaryOfficialA7AManagerImport($salaryManager->id, $salaryManager->start_date, $salaryManager->end_date), $excelFileA7A);
            }
            // if ($excelFileParttime != null && $salaryManager != null) {
            //     Excel::import(new SalaryParttimeManagerImport($salaryManager->id, $startDateParttime, $endDateParttime), $excelFileParttime);
            // }
            //save total
            $this->calculateTotal($salaryManager->id);
            DB::commit();
            toast('Import bảng lương thành công!', 'success', 'top-right');
            LogActivity::logRoleSpecificLoginActivity(auth()->user(), 'Admin Thêm Bảng Lương', 'Admin đã thêm bảng lương');
            return redirect()->route('admin.salary.home');
        } catch (\Exception $e) {
            DB::rollBack();
            LogHelper::saveLog('Import', $e->getMessage(), $e->getLine());
            Log::error('message:' . $e->getMessage());
            toast('Import bảng lương không thành công!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    public function calculateTotal($id)
    {
        $total = 0;
        $totalVVP = SalaryOfficialVVP::where('salaries_manager_id', $id)->sum('actually_received_payroll');
        $totalA7A = SalaryOfficialA7A::where('salaries_manager_id', $id)->sum('actually_received_payroll');
        // $totalParttime = SalaryParttime::where('salaries_manager_id', $id)->sum('actually_received');
        if ($totalVVP) {
            $total += $totalVVP;
        }
        if ($totalA7A) {
            $total += $totalA7A;
        }
        // if ($totalParttime) {
        //     $total += $totalParttime;
        // }
        $salaryManager = SalaryManager::find($id);
        $salaryManager->total = $total;
        $salaryManager->save();
    }

    public function detail($id)
    {
        $salaryOfficialsVVP = SalaryOfficialVVP::where('salaries_manager_id', $id)->paginate(SalaryOfficialVVP::paginate);
        $salaryOfficialsA7A = SalaryOfficialA7A::where('salaries_manager_id', $id)->paginate(SalaryOfficialA7A::paginate);
        $salaryParttimes = SalaryParttime::where('salaries_manager_id', $id)->paginate(SalaryParttime::paginate);
        return view('salary.detail', compact('salaryOfficialsVVP', 'salaryOfficialsA7A', 'salaryParttimes'));
    }

    public function delete($id)
    {
        try {

            $salaryOfficialsVVP = SalaryOfficialVVP::where('salaries_manager_id', $id)->get();
            $salaryOfficialsA7A = SalaryOfficialA7A::where('salaries_manager_id', $id)->get();
            // $salaryParttimes = SalaryParttime::where('salaries_manager_id', $id)->get();
            foreach ($salaryOfficialsVVP as $salaryOfficialVVP) {
                SalaryOfficialVVPTimekeeping::where('salary_official_vvp_id', $salaryOfficialVVP->id)->delete();
                SalaryOfficialVVP::where('id', $salaryOfficialVVP->id)->delete();
            }
            foreach ($salaryOfficialsA7A as $salaryOfficialA7A) {
                SalaryOfficialA7ATimekeeping::where('salary_official_a7a_id', $salaryOfficialA7A->id)->delete();
                SalaryOfficialA7A::where('id', $salaryOfficialA7A->id)->delete();
            }
            // foreach ($salaryParttimes as $salaryParttime) {
            //     SalaryParttimeTimekeeping::where('salary_parttime_id', $salaryParttime->id)->delete();
            //     SalaryParttime::where('id', $salaryParttime->id)->delete();
            // }
            SalaryManager::find($id)->delete();
            toast('Xoá bảng lương thành công!', 'success', 'top-right');
            LogActivity::logRoleSpecificLoginActivity(auth()->user(), 'Admin Xoá Bảng Lương', 'Admin đã xoá bảng lương');
            return redirect()->route('admin.salary.home');
        } catch (\Exception $e) {
            LogHelper::saveLog('Import', $e->getMessage(), $e->getLine());
            Log::error('message:' . $e->getMessage());
            toast('Xoá bảng lương không thành công!', 'error', 'top-right');
            return redirect()->route('admin.salary.home');
        }
    }
}
