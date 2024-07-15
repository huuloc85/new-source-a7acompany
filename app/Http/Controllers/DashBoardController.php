<?php

namespace App\Http\Controllers;

use App\Models\Celender;
use App\Models\CheckEmployee;
use App\Models\Employee;
use App\Models\LoginHistory;
use App\Models\Role;
use App\Models\SalaryManager;
use App\Models\Product;
use Carbon\Carbon;

class DashBoardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();
        $totalEmployee = Employee::where('role_id', '!=', 15)
            ->where('role_id', '!=', 16)
            ->where('role_id', '!=', 17)
            ->where('deleted_at', null)
            ->count();
        $totalRole = Role::where('id', '!=', 15)
            ->where('id', '!=', 16)
            ->where('id', '!=', 17)
            ->count();

        $topUsers = LoginHistory::select('employee_name')
            ->selectRaw('COUNT(*) as login_count')
            ->groupBy('employee_name')
            ->orderByDesc('login_count')
            ->limit(10)
            ->get();

        $totalSalary = SalaryManager::count();
        $totalCelender = Celender::count();
        $totalProduct = Product::count();
        $totalHistory = LoginHistory::whereDate('date', Carbon::today())->count();
        $totalCheckEmployee = CheckEmployee::whereDate('date', Carbon::today())->count();
        $salaryManagers = SalaryManager::orderBy('id', 'DESC')->limit(SalaryManager::paginate)->get();
        $celenders = Celender::orderBy('id', 'DESC')->limit(Celender::paginate)->get();
        return view('home', compact('totalEmployee', 'totalRole', 'totalSalary', 'totalCelender', 'startOfMonth', 'endOfMonth', 'totalHistory', 'topUsers', 'salaryManagers', 'celenders', 'totalProduct', 'totalCheckEmployee'));
    }
}
