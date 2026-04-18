<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\HandleError;
use App\Models\SalaryConfig;
use App\Models\SalaryManager;
use App\Services\SalaryCalculationService;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class SalaryWebController extends BaseController
{
    // ========================================================================
    // 1. SALARY CONFIG — Cấu hình tính lương
    // ========================================================================

    /**
     * Lấy tất cả cấu hình
     * GET /api/admin/salary-web/configs
     */
    public function getAllConfigs()
    {
        try {
            $configs = SalaryConfig::all();
            return response()->json([
                'success' => true,
                'data' => $configs
            ]);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    /**
     * Lấy cấu hình tính lương cho 1 công ty
     * GET /api/admin/salary-web/config/{company}
     */
    public function getConfig(string $company)
    {
        try {
            $config = SalaryConfig::where('company', $company)->firstOrFail();
            return response()->json([
                'success' => true,
                'data' => $config
            ]);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    /**
     * Cập nhật cấu hình tính lương
     * PUT /api/admin/salary-web/config/{company}
     */
    public function updateConfig(Request $request, string $company)
    {
        try {
            $config = SalaryConfig::where('company', $company)->firstOrFail();
            $config->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật cấu hình thành công',
                'data' => $config
            ]);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    // ========================================================================
    // 2. TẠO & DANH SÁCH BẢNG LƯƠNG
    // ========================================================================

    /**
     * Lấy danh sách các bảng lương
     * GET /api/admin/salary-web
     */
    public function index()
    {
        try {
            // Lấy danh sách bảng lương mới nhất xếp trước
            $salaries = SalaryManager::orderBy('id', 'desc')->get();
            return response()->json($salaries);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    /**
     * Tạo bảng lương mới và khởi tạo record cho nhân viên
     * POST /api/admin/salary-web/create
     */
    public function create(Request $request)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'title' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'companies' => 'required|array|min:1',       // ['a7a', 'vvp'] hoặc ['a7a']
                'companies.*' => 'in:a7a,vvp',
            ]);

            $salary = SalaryManager::create([
                'title' => $validated['title'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'date_show' => Carbon::createFromFormat('Y-m-d', $validated['end_date'])
                    ->addMonth()->startOfMonth()->format('Y-m-d'),
            ]);

            $initResults = [];
            foreach ($validated['companies'] as $company) {
                $service = new SalaryCalculationService($company);
                $initResults[$company] = $service->initializeSalaryPeriod(
                    $salary->id,
                    $validated['start_date'],
                    $validated['end_date']
                );
            }

            DB::commit();

            return response()->json([
                'message' => 'Tạo bảng lương thành công!',
                'salary_manager' => $salary,
                'initialization' => $initResults,
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return HandleError::handle($e);
        }
    }

    // ========================================================================
    // 3. CẬP NHẬT DANH MỤC (Thông tin lương cơ bản NV)
    // ========================================================================

    /**
     * Cập nhật thông tin lương 1 nhân viên (Sheet "Danh mục")
     * PUT /api/admin/salary-web/{salaryManagerId}/category/{employeeId}
     */
    public function updateCategory(Request $request, int $salaryManagerId, string $employeeId)
    {
        try {
            $company = $request->input('company', 'vvp');
            $service = new SalaryCalculationService($company);

            $result = $service->updateEmployeeCategory($salaryManagerId, $employeeId, $request->all());

            return response()->json([
                'message' => 'Cập nhật danh mục thành công!',
                'data' => $result,
            ]);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    /**
     * Cập nhật danh mục hàng loạt
     * PUT /api/admin/salary-web/{salaryManagerId}/category-bulk
     */
    public function bulkUpdateCategory(Request $request, int $salaryManagerId)
    {
        try {
            $validated = $request->validate([
                'company' => 'required|in:a7a,vvp',
                'employees' => 'required|array',
                'employees.*.employee_id' => 'required|integer',
            ]);

            $service = new SalaryCalculationService($validated['company']);
            $results = $service->bulkUpdateCategory($salaryManagerId, $validated['employees']);

            return response()->json([
                'message' => 'Cập nhật danh mục hàng loạt thành công!',
                'data' => $results,
            ]);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    // ========================================================================
    // 4. CHẤM CÔNG (Sheet "Bảng nhập công")
    // ========================================================================

    /**
     * Cập nhật chấm công 1 nhân viên
     * PUT /api/admin/salary-web/{salaryManagerId}/timekeeping/{employeeId}
     */
    public function updateTimekeeping(Request $request, int $salaryManagerId, string $employeeId)
    {
        try {
            $validated = $request->validate([
                'company' => 'required|in:a7a,vvp',
                'timekeeping_data' => 'required|array',
                'timekeeping_data.*.date' => 'required|date',
                'timekeeping_data.*.day_hours' => 'nullable|numeric|min:0|max:24',
                'timekeeping_data.*.night_hours' => 'nullable|numeric|min:0|max:24',
                'timekeeping_data.*.overtime_hours' => 'nullable|numeric|min:0|max:24',
            ]);

            $service = new SalaryCalculationService($validated['company']);
            $result = $service->updateTimekeeping($salaryManagerId, $employeeId, $validated['timekeeping_data']);

            return response()->json([
                'message' => 'Cập nhật chấm công thành công!',
                'data' => $result,
            ]);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    /**
     * Cập nhật chấm công hàng loạt (nhiều NV)
     * PUT /api/admin/salary-web/{salaryManagerId}/timekeeping-bulk
     */
    public function bulkUpdateTimekeeping(Request $request, int $salaryManagerId)
    {
        try {
            $validated = $request->validate([
                'company' => 'required|in:a7a,vvp',
                'data' => 'required|array',
                'data.*.employee_id' => 'required|integer',
                'data.*.timekeeping_data' => 'required|array',
                'data.*.timekeeping_data.*.date' => 'required|date',
                'data.*.timekeeping_data.*.day_hours' => 'nullable|numeric|min:0|max:24',
                'data.*.timekeeping_data.*.night_hours' => 'nullable|numeric|min:0|max:24',
                'data.*.timekeeping_data.*.overtime_hours' => 'nullable|numeric|min:0|max:24',
            ]);

            $service = new SalaryCalculationService($validated['company']);
            $results = $service->bulkUpdateTimekeeping($salaryManagerId, $validated['data']);

            return response()->json([
                'message' => 'Cập nhật chấm công hàng loạt thành công!',
                'data' => $results,
            ]);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    /**
     * Lấy chi tiết chấm công 1 nhân viên
     * GET /api/admin/salary-web/{salaryManagerId}/timekeeping/{employeeId}
     */
    public function getTimekeeping(Request $request, int $salaryManagerId, string $employeeId)
    {
        try {
            $company = $request->input('company', 'vvp');

            if ($company === 'a7a') {
                $salary = \App\Models\SalaryOfficialA7A::where('salaries_manager_id', $salaryManagerId)
                    ->where('employee_id', $employeeId)
                    ->with('SalaryOfficialA7ATimekeepings')
                    ->first();
                $timekeepings = $salary ? $salary->SalaryOfficialA7ATimekeepings : collect();
            } else {
                $salary = \App\Models\SalaryOfficialVVP::where('salaries_manager_id', $salaryManagerId)
                    ->where('employee_id', $employeeId)
                    ->with('SalaryOfficialVVPTimekeepings')
                    ->first();
                $timekeepings = $salary ? $salary->SalaryOfficialVVPTimekeepings : collect();
            }

            return response()->json([
                'employee_id' => $employeeId,
                'summary' => [
                    'total_day_hours' => $salary->total_day_offical ?? 0,
                    'total_night_hours' => $salary->total_night_offical ?? 0,
                    'total_overtime_hours' => $salary->total_overtime_offical ?? 0,
                    'rice_day_count' => $salary->allowance_rice_day_timekeeping ?? 0,
                    'night_shift_count' => $salary->allowance_rice_night_timekeeping ?? 0,
                    'overtime_day_count' => $salary->allowance_overtime_timekeeping ?? 0,
                    'holidays_count' => $salary->holidays_count ?? 0,
                    'paid_holidays_count' => $salary->paid_holidays_count ?? 0,
                    'days_leave_allowed' => $salary->daysleave_allowed_timekeeping ?? 0,
                    'days_leave_not_allowed' => $salary->daysleave_notallowed_timekeeping ?? 0,
                ],
                'daily' => $timekeepings->map(function ($t) {
                    return [
                        'id' => $t->id,
                        'date' => $t->timekeeping_date,
                        'day_hours' => $t->timekeeping_day,
                        'night_hours' => $t->timekeeping_night,
                        'overtime_hours' => $t->timekeeping_overtime,
                    ];
                })->sortBy('date')->values(),
            ]);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    // ========================================================================
    // 5. CẬP NHẬT THÔNG TIN BỔ SUNG
    // ========================================================================

    /**
     * Cập nhật thông tin bổ sung (nghỉ phép, tạm ứng, KPI...)
     * PUT /api/admin/salary-web/{salaryManagerId}/adjustments/{employeeId}
     */
    public function updateAdjustments(Request $request, int $salaryManagerId, string $employeeId)
    {
        try {
            $company = $request->input('company', 'vvp');
            $service = new SalaryCalculationService($company);

            $result = $service->updateAdjustments($salaryManagerId, $employeeId, $request->except('company'));

            return response()->json([
                'message' => 'Cập nhật thông tin bổ sung thành công!',
                'data' => $result,
            ]);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    // ========================================================================
    // 6. TÍNH LƯƠNG — Core API
    // ========================================================================

    /**
     * Tính lương cho 1 nhân viên
     * POST /api/admin/salary-web/{salaryManagerId}/calculate/{employeeId}
     */
    public function calculateOne(Request $request, int $salaryManagerId, string $employeeId)
    {
        try {
            $validated = $request->validate([
                'company' => 'required|in:a7a,vvp',
                'employee_type' => 'nullable|in:worker,office',
                'timekeeping_summary' => 'nullable|array',
                'timekeeping_daily' => 'nullable|array',
                'calculation_detail' => 'nullable|array',
                'payroll' => 'nullable|array',
                'salary_fields' => 'nullable|array',
                'sheet_all_columns' => 'nullable|array',
            ]);

            $company = $validated['company'];
            $employeeType = $validated['employee_type'] ?? 'worker';

            $service = new SalaryCalculationService($company);

            $isFeDrivenPayload = $request->hasAny([
                'timekeeping_summary',
                'timekeeping_daily',
                'calculation_detail',
                'payroll',
                'salary_fields',
                'sheet_all_columns',
            ]);

            if ($isFeDrivenPayload) {
                $result = $service->mapCalculatedForEmployee($salaryManagerId, $employeeId, $request->all());

                return response()->json([
                    'message' => 'Đã lưu dữ liệu FE tính thành công!',
                    'mode' => 'fe-driven-map',
                    'data' => $result,
                ]);
            }

            $result = $service->calculateForEmployee($salaryManagerId, $employeeId, $employeeType);

            return response()->json([
                'message' => 'Tính lương thành công!',
                'mode' => 'be-calculate',
                'data' => $result,
            ]);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    /**
     * Tính lương cho tất cả hoặc danh sách nhân viên
     * POST /api/admin/salary-web/{salaryManagerId}/calculate
     */
    public function calculateAll(Request $request, int $salaryManagerId)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        try {
            $validated = $request->validate([
                'company' => 'required|in:a7a,vvp',
                'employee_ids' => 'nullable|array',         // null = tất cả
                'employee_ids.*' => 'string',
                'calculated_data' => 'nullable|array',
                'calculated_data.*.employee_id' => 'required_with:calculated_data|string',
                'calculated_data.*.timekeeping_summary' => 'nullable|array',
                'calculated_data.*.timekeeping_daily' => 'nullable|array',
                'calculated_data.*.calculation_detail' => 'nullable|array',
                'calculated_data.*.payroll' => 'nullable|array',
                'calculated_data.*.salary_fields' => 'nullable|array',
                'calculated_data.*.sheet_all_columns' => 'nullable|array',
            ]);

            $service = new SalaryCalculationService($validated['company']);

            if (! empty($validated['calculated_data'])) {
                $result = $service->mapCalculatedBulk(
                    $salaryManagerId,
                    $validated['calculated_data']
                );

                return response()->json([
                    'message' => 'Đã lưu dữ liệu FE tính hàng loạt thành công!',
                    'mode' => 'fe-driven-map',
                    'data' => $result,
                ]);
            }

            $result = $service->calculateAll(
                $salaryManagerId,
                $validated['employee_ids'] ?? null
            );

            return response()->json([
                'message' => 'Tính lương thành công!',
                'mode' => 'be-calculate',
                'data' => $result,
            ]);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    /**
     * Đồng bộ dữ liệu máy chấm công 
     * POST /api/admin/salary-web/{salaryManagerId}/sync-attendance
     */
    public function syncAttendance(Request $request, int $salaryManagerId)
    {
        try {
            $validated = $request->validate([
                'company' => 'required|in:a7a,vvp',
            ]);

            // Gọi Job chạy ngầm dưới background (Redis) thay vì bắt Frontend chờ
            \App\Jobs\SyncAttendanceJob::dispatch($salaryManagerId, $validated['company']);

            return response()->json([
                'message' => 'Hệ thống đang tiến hành đẩy dữ liệu ngầm. Vui lòng tải lại trang sau 1-2 phút!',
            ]);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    // ========================================================================
    // 7. LẤY DỮ LIỆU BẢNG LƯƠNG (cho FE hiển thị)
    // ========================================================================

    /**
     * Lấy toàn bộ dữ liệu bảng lương đã tính
     * GET /api/admin/salary-web/{salaryManagerId}/data
     */
    public function getSalaryData(Request $request, int $salaryManagerId)
    {
        try {
            $company = $request->input('company', 'vvp');
            $service = new SalaryCalculationService($company);
            $data = $service->getSalaryData($salaryManagerId);

            return response()->json([
                'salary_manager' => SalaryManager::find($salaryManagerId),
                'company' => $company,
                'employees' => $data,
                'config' => $service->getConfigArray(),
            ]);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    /**
     * Lấy bảng công thức cho FE hiển thị
     * GET /api/admin/salary-web/formulas/{company}
     */
    public function getFormulas(string $company)
    {
        try {
            $service = new SalaryCalculationService($company);
            return response()->json($service->getFormulaDefinitions());
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    // ========================================================================
    // 8. TỔNG HỢP THANH TOÁN
    // ========================================================================

    /**
     * Lấy bảng thanh toán lương tổng hợp (Sheet "Bang Thanh Toan Luong")
     * GET /api/admin/salary-web/{salaryManagerId}/payroll-summary
     */
    public function getPayrollSummary(Request $request, int $salaryManagerId)
    {
        try {
            $company = $request->input('company', 'vvp');

            if ($company === 'a7a') {
                $salaries = \App\Models\SalaryOfficialA7A::where('salaries_manager_id', $salaryManagerId)
                    ->with('employee:id,name,role_id')
                    ->with('employee.role:id,role_name')
                    ->get();
            } else {
                $salaries = \App\Models\SalaryOfficialVVP::where('salaries_manager_id', $salaryManagerId)
                    ->with('employee:id,name,role_id')
                    ->with('employee.role:id,role_name')
                    ->get();
            }

            $allPayments = [];
            $transferPayments = [];
            $cashPayments = [];

            foreach ($salaries as $salary) {
                $item = [
                    'employee_id' => $salary->employee_id,
                    'employee_name' => $salary->employee->name ?? '',
                    'department' => $salary->employee->role->role_name ?? '',
                    'total_income' => $salary->salary_total,
                    'insurance_deduction' => $salary->insurance_payroll,
                    'union_fee' => ($salary->salary_basic ?? 0) * 0.005,
                    'advance_money' => $salary->advance_money_payroll,
                    'kpi_deduction' => $salary->KPI_Subtraction_payroll,
                    'previous_debt' => $salary->previous_period_debt_payroll,
                    'actually_received' => $salary->actually_received_payroll,
                    'company_insurance' => $salary->company_insurance_payroll,
                ];

                $allPayments[] = $item;

                $paymentMethod = $salary->forms_of_payment ?? 'Chuyển khoản';
                if (mb_strtolower($paymentMethod) === 'tiền mặt') {
                    $cashPayments[] = $item;
                } else {
                    $transferPayments[] = $item;
                }
            }

            return response()->json([
                'salary_manager' => SalaryManager::find($salaryManagerId),
                'company' => $company,
                'all_payments' => $allPayments,
                'transfer_payments' => $transferPayments,
                'cash_payments' => $cashPayments,
                'totals' => [
                    'transfer_count' => count($transferPayments),
                    'transfer_total' => array_sum(array_column($transferPayments, 'actually_received')),
                    'cash_count' => count($cashPayments),
                    'cash_total' => array_sum(array_column($cashPayments, 'actually_received')),
                    'grand_total' => array_sum(array_column($transferPayments, 'actually_received'))
                        + array_sum(array_column($cashPayments, 'actually_received')),
                ],
            ]);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }


}
