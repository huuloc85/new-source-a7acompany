<?php

namespace App\Http\Controllers\Api\Employee;

use App\Helpers\HandleError;
use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Models\SalaryManager;
use App\Models\SalaryOfficialA7A;
use App\Models\SalaryOfficialVVP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class EmpSalaryController extends Controller
{
    public function empSalaries(Request $request)
    {
        try {
            $salaries = QueryBuilder::for(SalaryManager::class)
                ->select('id', 'title', 'start_date', 'end_date', 'date_show', 'created_at', 'updated_at')
                ->allowedFilters('title', 'start_date', 'end_date', 'date_show', 'created_at', 'updated_at')
                ->defaultSort('-id')
                ->allowedSorts(['id', 'title', 'start_date', 'end_date', 'date_show', 'created_at', 'updated_at']);

            $limit = $request->limit;
            if (! is_null($limit) && $limit == 0) {
                $limit = $salaries->count();
            }
            $salaries = $salaries->paginate($limit ?? 10);

            LogActivity::logViewActivity(auth()->user(), 'Xem Danh Sách Lương', 'Nhân viên xem danh sách bảng lương');

            return response()->json($salaries);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    public function empSalary($id)
    {
        $user = Auth::user();
        try {
            $company = strtolower($user->company);
            if ($company === 'a7a') {
                $salary = SalaryOfficialA7A::query()
                    ->where('salaries_manager_id', $id)
                    ->where('employee_id', $user->id)
                    ->with([
                        'employee.role:id,role_name',
                        'employee:id,name,role_id,company',
                        'salaryManager:id,title,start_date,end_date,date_show,created_at,updated_at',
                    ])
                    ->firstOrFail();
            } else {
                $salary = SalaryOfficialVVP::query()
                    ->where('salaries_manager_id', $id)
                    ->where('employee_id', $user->id)
                    ->with([
                        'employee.role:id,role_name',
                        'employee:id,name,role_id,company',
                        'salaryManager:id,title,start_date,end_date,date_show,created_at,updated_at',
                    ])
                    ->firstOrFail();
            }

            LogActivity::logViewActivity(auth()->user(), 'Xem Chi Tiết Lương', 'Nhân viên xem chi tiết bảng lương cá nhân');

            return response()->json($salary);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }
}
