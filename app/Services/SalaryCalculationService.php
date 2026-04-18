<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\SalaryConfig;
use App\Models\SalaryManager;
use App\Models\SalaryOfficialA7A;
use App\Models\SalaryOfficialA7ATimekeeping;
use App\Models\SalaryOfficialVVP;
use App\Models\SalaryOfficialVVPTimekeeping;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalaryCalculationService
{
    private SalaryConfig $config;
    private string $company;

    public function __construct(string $company)
    {
        $this->company = $company;
        $this->config = SalaryConfig::where('company', $company)->firstOrFail();
    }

    /**
     * Đồng bộ dữ liệu máy chấm công từ AttendanceCalculationController
     */
    public function syncAttendanceData(int $salaryManagerId): void
    {
        $manager = \App\Models\SalaryManager::findOrFail($salaryManagerId);

        $startDate = \Illuminate\Support\Carbon::parse($manager->start_date)->startOfDay();
        $endDate = \Illuminate\Support\Carbon::parse($manager->end_date)->endOfDay();

        $model = $this->getOfficialModel();
        $salaries = $model::where('salaries_manager_id', $salaryManagerId)->get();

        // Giả lập Request gửi đến controller tính toán Attendance
        $request = new \Illuminate\Http\Request();
        $request->merge([
            'filter' => [
                'date_between' => $startDate->format('Y-m-d') . ',' . $endDate->format('Y-m-d'),
                'employees.company' => $this->company,
            ],
            'limit' => 0 // Lấy tất cả, không phân trang giới hạn
        ]);

        $attendanceController = new \App\Http\Controllers\Api\Admin\AttendanceCalculationController();
        $response = $attendanceController->calculate($request);
        
        $content = json_decode($response->getContent(), true) ?? [];
        $calculatedData = $content['data'] ?? ($content ?? []);

        // Gom nhóm theo employee_id để lặp mảng 1 lần
        $attendanceByEmployee = [];
        foreach ($calculatedData as $row) {
            if (isset($row['employee_id'])) {
                $attendanceByEmployee[$row['employee_id']][] = $row;
            }
        }

        $fk = $this->getTimekeepingForeignKey();
        $timekeepingModel = $this->getTimekeepingModel();

        foreach ($salaries as $salary) {
            $empId = $salary->employee_id;
            $records = $attendanceByEmployee[$empId] ?? [];

            // 1. Clears old details
            $timekeepingModel::where($fk, $salary->id)->delete();

            $totalDay = 0;
            $totalNight = 0;
            $totalOvertime = 0;

            // 2. Insert new details
            $timekeepingData = [];
            $now = now();

            foreach ($records as $record) {
                $dayHours = 0;
                $nightHours = 0;
                if ($record['shift'] === 1) {
                    $dayHours = $record['administrative_hours'];
                } else if ($record['shift'] === 2) {
                    $nightHours = $record['administrative_hours'];
                }

                $timekeepingData[] = [
                    $fk => $salary->id,
                    'timekeeping_date' => $record['date'],
                    'timekeeping_day' => $dayHours,
                    'timekeeping_night' => $nightHours,
                    'timekeeping_overtime' => $record['overtime_hours'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                $totalDay += $dayHours;
                $totalNight += $nightHours;
                $totalOvertime += $record['overtime_hours'];
            }

            if (!empty($timekeepingData)) {
                // Chia nhỏ chunk insert phòng khi db query array quá dài, dù 30 dòng thì không sao
                foreach (array_chunk($timekeepingData, 100) as $chunk) {
                    $timekeepingModel::insert($chunk);
                }
            }

            // 3. Update summary variables
            $salary->total_day_offical = $totalDay > 0 ? $totalDay : null;
            $salary->total_night_offical = $totalNight > 0 ? $totalNight : null;
            $salary->total_overtime_offical = $totalOvertime > 0 ? $totalOvertime : null;
            $salary->save();

            // 4. Force recount other timekeeping formulas
            $this->recalculateTimekeepingSummary($salary);
        }
    }

    /**
     * Lấy salary config dạng array cho FE
     */
    public function getConfigArray(): array
    {
        return [
            'company' => $this->config->company,
            'company_name' => $this->config->company_name,
            'max_work_days_worker' => $this->config->max_work_days_worker,
            'max_work_days_office' => $this->config->max_work_days_office,
            'max_hours_worker' => $this->config->max_hours_worker,
            'max_hours_office' => $this->config->max_hours_office,
            'standard_work_days' => $this->config->standard_work_days,
            'hours_per_day' => $this->config->hours_per_day,
            'hourly_divisor' => $this->config->hourly_divisor,
            'overtime_multiplier' => $this->config->overtime_multiplier,
            'insurance_company_rate' => $this->config->insurance_company_rate,
            'insurance_employee_rate' => $this->config->insurance_employee_rate,
            'union_fee_rate' => $this->config->union_fee_rate,
            'rounding_unit' => $this->config->rounding_unit,
        ];
    }

    // ========================================================================
    // STEP 1: TẠO BẢNG LƯƠNG MỚI & TẠO RECORD CHO TẤT CẢ NHÂN VIÊN
    // ========================================================================

    /**
     * Tạo bảng lương mới và khởi tạo record cho tất cả NV thuộc công ty
     * Giống việc mở file Excel mới: tạo Danh mục + Bảng nhập công trống
     */
    public function initializeSalaryPeriod(int $salaryManagerId, string $startDate, string $endDate): array
    {
        $employees = Employee::where('company', $this->company)
            ->with('role:id,role_name')
            ->get();

        $model = $this->getOfficialModel();
        $timekeepingModel = $this->getTimekeepingModel();
        $foreignKey = $this->getTimekeepingForeignKey();

        $created = [];

        foreach ($employees as $employee) {
            // Kiểm tra đã tồn tại chưa
            $exists = $model::where('salaries_manager_id', $salaryManagerId)
                ->where('employee_id', $employee->id)
                ->exists();

            if (!$exists) {
                // Tạo record danh mục (lương cơ bản) từ thông tin nhân viên
                $salary = $model::create([
                    'salaries_manager_id' => $salaryManagerId,
                    'employee_id' => $employee->id,
                ]);

                // Tạo timekeeping trống cho từng ngày
                $dateStart = Carbon::parse($startDate);
                $dateEnd = Carbon::parse($endDate);
                $timekeepingData = [];

                for ($date = $dateStart->copy(); $date->lte($dateEnd); $date->addDay()) {
                    $timekeepingData[] = [
                        $foreignKey => $salary->id,
                        'timekeeping_date' => $date->format('Y-m-d'),
                        'timekeeping_day' => null,
                        'timekeeping_night' => null,
                        'timekeeping_overtime' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if (!empty($timekeepingData)) {
                    $timekeepingModel::insert($timekeepingData);
                }

                $created[] = $employee->id;
            }
        }

        return [
            'initialized' => count($created),
            'employee_ids' => $created,
            'total_employees' => $employees->count(),
        ];
    }

    // ========================================================================
    // STEP 2: CẬP NHẬT DANH MỤC (thông tin lương cơ bản NV)
    // ========================================================================

    /**
     * Cập nhật thông tin lương cơ bản cho nhân viên (Sheet "Danh mục")
     * FE gửi các thông tin lương, phụ cấp. API sẽ tự tính các giá trị derived.
     *
     * Tương ứng Excel:
     * - N: salary_basic (Lương CB chính thức/26 ngày)
     * - O: regular_salary_hour = N / 204 (tự tính)
     * - P: salary_overtime = O × 1.5 (tự tính)
     * - W: company_insurance = N × 21.5% (tự tính nếu has_insurance)
     * - X: insurance = N × 10.5% (tự tính nếu has_insurance)
     */
    public function updateEmployeeCategory(int $salaryManagerId, string $employeeId, array $data): array
    {
        $salary = $this->findOrCreateSalaryRecord($salaryManagerId, $employeeId);

        // Các field input trực tiếp (giống nhập vào Excel "Danh mục")
        $directFields = [
            'salary_day',                             // H: Lương ngày (thử việc)
            'salary_night',                           // I: Lương đêm (thử việc)
            'probationary_salary_basic_26days',       // J: Lương CB thử việc / 26 ngày
            'probationary_salary_basic_hours',        // K: Lương CB thử việc / 1 giờ
            'probationary_salary_basic_extra_hours',  // L: Lương CB thử việc tăng ca / 1 giờ
            'allowance_apprentice',                   // M: Phụ cấp học việc
            'salary_basic',                           // N: Lương CB chính thức / 26 ngày ★
            'allowance_diligence',                    // Q: Chuyên cần
            'allowance_responsibility',               // S: Trách nhiệm
            'allowance_overtime',                     // T: Phụ cấp tăng ca / ngày
            'allowance_night',                        // U: Phụ cấp đêm
            'allowance_rice',                         // V: Phụ cấp cơm trưa
            'employee_type',                          // Loại nhân viên (office/worker) ★
        ];

        foreach ($directFields as $field) {
            if (array_key_exists($field, $data)) {
                $salary->$field = $data[$field];
            }
        }

        // ===== KIỂM TRA TOÀN VẸN CẤU TRÚC (STRUCTURAL INTEGRITY) =====
        // Nếu đã có "Lương CB chính thức", thì tuyệt đối không áp dụng Lương thử việc
        $salaryBasic = (float)($salary->salary_basic ?? 0);
        if ($salaryBasic > 0) {
            $salary->salary_day = 0;
            $salary->salary_night = 0;
            $salary->probationary_salary_basic_26days = 0;
            $salary->probationary_salary_basic_hours = 0;
            $salary->probationary_salary_basic_extra_hours = 0;
        }

        // ===== TỰ ĐỘNG TÍNH CÁC GIÁ TRỊ DẪN XUẤT (formulas từ Excel) =====
        $salaryBasic = (float)($salary->salary_basic ?? 0);

        // ===== XỬ LÝ TOGGLE BẢO HIỂM (has_insurance) =====
        // Ưu tiên: FE gửi → dùng giá trị FE (cast boolean chặt)
        // Không gửi → dùng giá trị đã lưu trong DB
        if (array_key_exists('has_insurance', $data)) {
            $hasInsurance = filter_var($data['has_insurance'], FILTER_VALIDATE_BOOLEAN);
        } else {
            $hasInsurance = (bool)$salary->has_insurance;
        }
        $salary->has_insurance = $hasInsurance;

        // O: Lương CB/giờ = salary_basic / 204
        // Excel: =N8/204
        $salary->regular_salary_hour = $salaryBasic > 0
            ? $salaryBasic / $this->config->hourly_divisor
            : 0;

        // P: Lương TC/giờ = regular_salary_hour × 1.5
        // Excel: =O8*1.5
        $salary->salary_overtime = $salary->regular_salary_hour * $this->config->overtime_multiplier;

        // W: BHXH CTY đóng = salary_basic × 21.5% (nếu đóng BHXH)
        // Excel: =IF(Y8="",0,(N8*21.5%))
        $salary->company_insurance = $hasInsurance
            ? $salaryBasic * $this->config->insurance_company_rate
            : 0;

        // X: BHXH NLĐ đóng = salary_basic × 10.5% (nếu đóng BHXH)
        // Excel: =IF(Y8="",0,(N8*10.5%))
        $salary->insurance = $hasInsurance
            ? $salaryBasic * $this->config->insurance_employee_rate
            : 0;

        $salary->save();

        return $salary->toArray();
    }

    /**
     * Cập nhật danh mục hàng loạt (bulk update all employees)
     */
    public function bulkUpdateCategory(int $salaryManagerId, array $employeesData): array
    {
        $results = [];
        foreach ($employeesData as $empData) {
            $employeeId = $empData['employee_id'];
            unset($empData['employee_id']);
            try {
                $results[] = $this->updateEmployeeCategory($salaryManagerId, $employeeId, $empData);
            } catch (\Exception $e) {
                $results[] = ['employee_id' => $employeeId, 'error' => $e->getMessage()];
            }
        }
        return $results;
    }

    // ========================================================================
    // STEP 3: NHẬP CHẤM CÔNG HÀNG NGÀY
    // ========================================================================

    /**
     * Cập nhật chấm công cho 1 nhân viên
     * Giống việc nhập dữ liệu hàng ngày vào sheet "Bảng nhập công"
     *
     * @param array $timekeepingData [
     *   ['date' => '2026-03-16', 'day_hours' => 8, 'night_hours' => 0, 'overtime_hours' => 2],
     *   ...
     * ]
     */
    public function updateTimekeeping(int $salaryManagerId, string $employeeId, array $timekeepingData): array
    {
        $timekeepingModel = $this->getTimekeepingModel();
        $foreignKey = $this->getTimekeepingForeignKey();
        $salary = $this->findOrCreateSalaryRecord($salaryManagerId, $employeeId);

        $updated = 0;
        $created = 0;

        foreach ($timekeepingData as $dayData) {
            $date = $dayData['date'];
            $existing = $timekeepingModel::where($foreignKey, $salary->id)
                ->where('timekeeping_date', $date)
                ->first();

            if ($existing) {
                $existing->timekeeping_day = $dayData['day_hours'] ?? null;
                $existing->timekeeping_night = $dayData['night_hours'] ?? null;
                $existing->timekeeping_overtime = $dayData['overtime_hours'] ?? null;
                $existing->save();
                $updated++;
            } else {
                $timekeepingModel::create([
                    $foreignKey => $salary->id,
                    'timekeeping_date' => $date,
                    'timekeeping_day' => $dayData['day_hours'] ?? null,
                    'timekeeping_night' => $dayData['night_hours'] ?? null,
                    'timekeeping_overtime' => $dayData['overtime_hours'] ?? null,
                ]);
                $created++;
            }
        }

        // Tự động tính tổng hợp chấm công (giống SUM formulas trong Excel)
        $this->recalculateTimekeepingSummary($salary);

        return [
            'employee_id' => $employeeId,
            'updated' => $updated,
            'created' => $created,
            'summary' => $this->getTimekeepingSummary($salary),
        ];
    }

    /**
     * Cập nhật chấm công hàng loạt cho nhiều nhân viên
     */
    public function bulkUpdateTimekeeping(int $salaryManagerId, array $allTimekeepingData): array
    {
        $results = [];
        foreach ($allTimekeepingData as $empTimekeeping) {
            $employeeId = $empTimekeeping['employee_id'];
            try {
                $results[] = $this->updateTimekeeping(
                    $salaryManagerId,
                    $employeeId,
                    $empTimekeeping['timekeeping_data']
                );
            } catch (\Exception $e) {
                $results[] = ['employee_id' => $employeeId, 'error' => $e->getMessage()];
            }
        }
        return $results;
    }

    /**
     * Tính lại tổng hợp chấm công (tương ứng cột E, F, G, K, L, M trong "Bảng nhập công")
     *
     * Excel formulas:
     * E (Tổng giờ ngày):  SUM giờ ngày > 1 qua tất cả các ngày
     * F (Tổng giờ đêm):   SUM giờ đêm > 1 qua tất cả các ngày
     * G (Tổng tăng ca):   SUM tất cả giờ tăng ca
     * K (Cơm ngày):       Đếm ngày có giờ ngày >= 5
     * L (Ca đêm):         Đếm đêm có giờ đêm >= 8
     * M (Tăng ca count):  Đếm ngày có tăng ca >= 8
     */
    private function recalculateTimekeepingSummary($salary): void
    {
        $timekeepingModel = $this->getTimekeepingModel();
        $foreignKey = $this->getTimekeepingForeignKey();

        $records = $timekeepingModel::where($foreignKey, $salary->id)->get();

        $totalDay = 0;
        $totalNight = 0;
        $totalOvertime = 0;
        $trialDayCount = 0;     // H: workday_count_trial (ngày thử việc)
        $trialNightCount = 0;   // I: worknight_count_trial
        $riceDay = 0;           // K: allowance_rice_day_timekeeping
        $riceNight = 0;         // L: allowance_rice_night_timekeeping (ca đêm)
        $otDayCount = 0;        // M: allowance_overtime_timekeeping
        $holidaysCount = 0;     // DC: holidays_count
        $paidHolidaysCount = 0; // DD: paid_holidays_count
        $daysLeaveAllowed = 0;  // DE: daysleave_allowed_timekeeping
        $daysLeaveNotAllowed = 0; // DF: daysleave_notallowed_timekeeping

        foreach ($records as $record) {
            $day = (float)($record->timekeeping_day ?? 0);
            $night = (float)($record->timekeeping_night ?? 0);
            $ot = (float)($record->timekeeping_overtime ?? 0);

            // E: Tổng giờ ngày (chỉ tính giá trị > 1 — theo Excel: IF(OR(N8="TC",N8<=1),0,N8))
            if ($day > 1) {
                $totalDay += $day;
            }

            // F: Tổng giờ đêm (chỉ tính giá trị > 1)
            if ($night > 1) {
                $totalNight += $night;
            }

            // G: Tổng tăng ca
            $totalOvertime += $ot;

            // H: Thử việc ngày (day <= 1 và day > 0 → tính là thử việc)
            if ($day > 0 && $day <= 1) {
                $trialDayCount += $day;
            }

            // I: Thử việc đêm
            if ($night > 0 && $night <= 1) {
                $trialNightCount += $night;
            }

            // K: Đếm ngày ăn cơm (giờ ngày >= 5)
            // Excel: =IF(N8>=5,1,0)+IF(Q8>=5,1,0)+...
            if ($day >= 5) {
                $riceDay++;
            }

            // L: Đếm ca đêm (giờ đêm >= 8)
            // Excel: =IF(O8>=8,1,0)+IF(R8>=8,1,0)+...
            if ($night >= 8) {
                $riceNight++;
            }

            // M: Đếm ngày tăng ca (giờ TC >= 8 hoặc 8.25, 8.5...)
            // Excel: =COUNTIF(N8:DB8,8)+COUNTIF(N8:DB8,8.25)+...
            if ($day >= 8) {
                $otDayCount++;
            }
        }

        // Cập nhật summary vào bản ghi chính
        $salary->total_day_offical = $totalDay;
        $salary->total_night_offical = $totalNight;
        $salary->total_overtime_offical = $totalOvertime;
        $salary->workday_count_trial = $trialDayCount;
        $salary->worknight_count_trial = $trialNightCount;
        $salary->allowance_rice_day_timekeeping = $riceDay;
        $salary->allowance_rice_night_timekeeping = $riceNight;
        $salary->allowance_overtime_timekeeping = $otDayCount;
        $salary->save();
    }

    /**
     * Lấy summary chấm công cho 1 nhân viên
     */
    private function getTimekeepingSummary($salary): array
    {
        return [
            'total_day_hours' => (float)($salary->total_day_offical ?? 0),
            'total_night_hours' => (float)($salary->total_night_offical ?? 0),
            'total_overtime_hours' => (float)($salary->total_overtime_offical ?? 0),
            'trial_day_count' => (float)($salary->workday_count_trial ?? 0),
            'trial_night_count' => (float)($salary->worknight_count_trial ?? 0),
            'rice_day_count' => (float)($salary->allowance_rice_day_timekeeping ?? 0),
            'night_shift_count' => (float)($salary->allowance_rice_night_timekeeping ?? 0),
            'overtime_day_count' => (float)($salary->allowance_overtime_timekeeping ?? 0),
            'holidays_count' => (float)($salary->holidays_count ?? 0),
            'paid_holidays_count' => (float)($salary->paid_holidays_count ?? 0),
            'days_leave_allowed' => (float)($salary->daysleave_allowed_timekeeping ?? 0),
            'days_leave_not_allowed' => (float)($salary->daysleave_notallowed_timekeeping ?? 0),
        ];
    }

    /**
     * Trả toàn bộ cột salary theo model để FE map đúng các tab/sheet cũ.
     */
    private function extractSalaryColumns($salary): array
    {
        $columns = [];
        foreach ($salary->getFillable() as $field) {
            $columns[$field] = $salary->{$field};
        }

        return $columns;
    }

    /**
     * Tìm record salary theo kỳ lương + employee.
     * Nếu chưa có thì tự khởi tạo tối thiểu để tránh văng ModelNotFoundException.
     */
    private function findOrCreateSalaryRecord(int $salaryManagerId, string $employeeId)
    {
        $model = $this->getOfficialModel();

        $salary = $model::where('salaries_manager_id', $salaryManagerId)
            ->where(function ($query) use ($employeeId) {
                $query->where('employee_id', $employeeId)->orWhere('id', $employeeId);
            })
            ->first();

        if ($salary) {
            return $salary;
        }

        $employee = Employee::where('id', $employeeId)
            ->where('company', $this->company)
            ->first();

        if (! $employee) {
            $existingEmployee = Employee::find($employeeId);
            if ($existingEmployee) {
                throw ValidationException::withMessages([
                    'company' => ["Employee #{$employeeId} thuộc company '{$existingEmployee->company}', không phải '{$this->company}'."],
                ]);
            }

            throw ValidationException::withMessages([
                'employee_id' => ["Employee #{$employeeId} không tồn tại."],
            ]);
        }

        $salary = $model::create([
            'salaries_manager_id' => $salaryManagerId,
            'employee_id' => $employee->id,
        ]);

        $this->initializeMissingTimekeepingRows($salaryManagerId, $salary->id);

        return $salary;
    }

    /**
     * Tạo sẵn dòng timekeeping theo ngày cho record salary mới khởi tạo.
     */
    private function initializeMissingTimekeepingRows(int $salaryManagerId, int $salaryId): void
    {
        $manager = SalaryManager::find($salaryManagerId);
        if (! $manager) {
            return;
        }

        $timekeepingModel = $this->getTimekeepingModel();
        $foreignKey = $this->getTimekeepingForeignKey();

        if ($timekeepingModel::where($foreignKey, $salaryId)->exists()) {
            return;
        }

        $startDate = Carbon::parse($manager->start_date)->startOfDay();
        $endDate = Carbon::parse($manager->end_date)->startOfDay();
        $rows = [];
        $now = now();

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $rows[] = [
                $foreignKey => $salaryId,
                'timekeeping_date' => $date->format('Y-m-d'),
                'timekeeping_day' => null,
                'timekeeping_night' => null,
                'timekeeping_overtime' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (! empty($rows)) {
            $timekeepingModel::insert($rows);
        }
    }

    // ========================================================================
    // STEP 4: CẬP NHẬT THÔNG TIN BỔ SUNG (input thủ công)
    // ========================================================================

    /**
     * Cập nhật thông tin bổ sung cho nhân viên:
     * - Nghỉ lễ, phép năm, nghỉ có/không phép
     * - Tạm ứng, KPI, lỗi nặng/nhẹ
     * - Phụ cấp công tác, giới thiệu, thưởng...
     */
    public function updateAdjustments(int $salaryManagerId, string $employeeId, array $data): array
    {
        $salary = $this->findOrCreateSalaryRecord($salaryManagerId, $employeeId);

        // Các field input thủ công — FE gửi trực tiếp, không cần công thức
        $adjustmentFields = [
            // Nghỉ phép (từ sheet "Bảng nhập công" cột DC, DD, DE, DF)
            'holidays_count',                    // DC: Số ngày lễ tết
            'paid_holidays_count',               // DD: Số ngày phép năm
            'daysleave_allowed_timekeeping',     // DE: Nghỉ có phép
            'daysleave_notallowed_timekeeping',  // DF: Nghỉ không phép

            // Công tác (từ sheet "Bảng tính toán")
            'business_travel_hours',             // AU: Số giờ đi công tác
            'business_travel_unit_price_hour',   // AV: Đơn giá đi công tác/ giờ
            'number_of_business_trips',          // AY: Số lần đi công tác
            'business_fuel_unit_price_day',      // AZ: Đơn giá xăng công tác/ ngày

            // Các khoản bổ sung
            'money_referral_people',             // BC: Tiền giới thiệu người
            'allowance_diffrent',                // BE: Phụ cấp khác
            'bonuses_for_attendance',            // BG: Tiền thưởng đạt chuyên cần
            'sickness',                          // Ốm đau (nghỉ hưởng lương)
            'funeral',                           // Ma chay
            'birthday_money',                    // BK: Tiền sinh nhật
            'previous_period_debt',              // BM: Tiền lương tháng trước bị thiếu

            // Khấu trừ
            'advance_money',                     // BR: Tạm ứng
            'error_serious',                     // BZ: Số lỗi nặng
            'error_minor',                       // CB: Số lỗi nhẹ

            // Hình thức thanh toán
            'forms_of_payment',                  // CG: Chuyển khoản / Tiền mặt

            // CHOP - GHI CHÚ
            'official_salary_notice',
            'overtime_salary_notice',
            'allowance_diligence_detail_notice',
            'allowance_responsibility_detail_notice',
            'allowance_overtime_detail_notice',
            'paid_holidays_money_notice',
            'holidays_money_notice',
            'allowance_diffrent_notice',
            'advance_money_notice',
            'unicon_deduction_notice',
            'kpi_subtraction_notice',
            'previous_period_debt_notice',
            'bonuses_for_attendance_notice',
            'subtract_error_serious_notice',
            'subtract_error_minor_notice'
        ];

        foreach ($adjustmentFields as $field) {
            if (array_key_exists($field, $data)) {
                $salary->$field = $data[$field];
            }
        }

        $salary->save();

        return $salary->toArray();
    }

    // ========================================================================
    // STEP 5: TÍNH LƯƠNG — CORE CALCULATION (khớp 100% Excel formulas)
    // ========================================================================

    /**
     * Tính lương cho 1 nhân viên
     * Tương ứng toàn bộ sheet "Bảng tính toán" trong Excel
     *
     * @param string $employeeType 'worker'|'office' — để chọn ngưỡng max hours
     */
    public function calculateForEmployee(int $salaryManagerId, string $employeeId, string $employeeType = 'worker'): array
    {
        $salary = $this->findOrCreateSalaryRecord($salaryManagerId, $employeeId);
        $salary->load('employee.role');

        // Lấy config
        $maxHours = $employeeType === 'office'
            ? $this->config->max_hours_office
            : $this->config->max_hours_worker;
        $standardDays = $this->config->standard_work_days;  // 26
        $hoursPerDay = $this->config->hours_per_day;        // 8
        $hourlyDivisor = $this->config->hourly_divisor;     // 204
        $otMultiplier = $this->config->overtime_multiplier; // 1.5
        $roundingUnit = $this->config->rounding_unit;       // 1000

        // ===== DANH MỤC (nguồn từ Category) =====
        $salaryBasic = (float)($salary->salary_basic ?? 0);
        $salaryDay = (float)($salary->salary_day ?? 0);
        $salaryNight = (float)($salary->salary_night ?? 0);
        $probOtRate = (float)($salary->probationary_salary_basic_extra_hours ?? 0);

        // ===== KIỂM TRA TOÀN VẸN CẤU TRÚC (STRUCTURAL INTEGRITY) =====
        if ($salaryBasic > 0) {
            $salaryDay = 0;
            $salaryNight = 0;
            $probOtRate = 0;
        }

        $allowanceApprentice = (float)($salary->allowance_apprentice ?? 0);
        $diligenceRate = (float)($salary->allowance_diligence ?? 0);
        $responsibilityRate = (float)($salary->allowance_responsibility ?? 0);
        $allowanceOvertimePerDay = (float)($salary->allowance_overtime ?? 0);
        $allowanceNight = (float)($salary->allowance_night ?? 0);
        $allowanceRice = (float)($salary->allowance_rice ?? 0);

        // O: Lương CB/giờ — Excel: =N/204
        $hourlyRate = $salaryBasic > 0 ? $salaryBasic / $hourlyDivisor : 0;

        // P: Lương TC/giờ — Excel: =O*1.5
        $otRate = $hourlyRate * $otMultiplier;

        // ===== CHẤM CÔNG (nguồn từ Timekeeping summary) =====
        $totalDayHours = (float)($salary->total_day_offical ?? 0);
        $totalNightHours = (float)($salary->total_night_offical ?? 0);
        $totalOvertimeHours = (float)($salary->total_overtime_offical ?? 0);
        $trialDayCount = (float)($salary->workday_count_trial ?? 0);
        $trialNightCount = (float)($salary->worknight_count_trial ?? 0);
        $riceDayCount = (float)($salary->allowance_rice_day_timekeeping ?? 0);
        $nightShiftCount = (float)($salary->allowance_rice_night_timekeeping ?? 0);
        $otDayCount = (float)($salary->allowance_overtime_timekeeping ?? 0);
        $holidaysCount = (float)($salary->holidays_count ?? 0);
        $paidLeaveCount = (float)($salary->paid_holidays_count ?? 0);
        $daysLeaveAllowed = (float)($salary->daysleave_allowed_timekeeping ?? 0);
        $daysLeaveNotAllowed = (float)($salary->daysleave_notallowed_timekeeping ?? 0);

        // ================== A. THỬ VIỆC (Cột E→O) ==================

        // E: Số công ngày thử việc
        $salary->number_of_work_days_trial = $trialDayCount;

        // F: Lương ca ngày thử việc — Excel: =$E8*'Danh muc'!$H8
        $trialDaySalary = $trialDayCount * $salaryDay;
        $salary->day_shift_salary_trial = $trialDaySalary;

        // H: Số công đêm thử việc
        $salary->number_of_work_nights_trial = $trialNightCount;

        // I: Lương ca đêm thử việc — Excel: =$H8*'Danh muc'!$I8
        $trialNightSalary = $trialNightCount * $salaryNight;
        $salary->night_shift_salary_trial = $trialNightSalary;

        // K: Số giờ tăng ca thử việc — Excel: =' Bảng nhập công'!J
        $trialOtHours = (float)($salary->overtime_hours_trial ?? 0);

        // L: Lương tăng ca thử việc — Excel: ='Danh muc'!L*K
        $trialOtSalary = $probOtRate * $trialOtHours;
        $salary->overtime_salary_trial = $trialOtSalary;

        // N: Số công học việc
        $numberWork = (float)($salary->number_of_work ?? 0);

        // O: Phụ cấp học việc — Excel: ='Danh muc'!M/26*N
        $apprenticeAllowance = $allowanceApprentice > 0
            ? ($allowanceApprentice / $standardDays) * $numberWork
            : 0;
        $salary->allowance_apprentice_detail = $apprenticeAllowance;

        // ================== B. CHÍNH THỨC (Cột Q→AD) ==================

        // Q: Số giờ chính — Excel: =MIN(E+F, MAX_HOURS)
        // =IF((' Bảng nhập công'!$E+' Bảng nhập công'!$F)>='Bảng tính toán'!$Q$5,$Q$5,(E+F))
        $totalWorkedHours = $totalDayHours + $totalNightHours;
        $coreHours = min($totalWorkedHours, $maxHours);
        $salary->core_hours = $coreHours;

        // R: Lương căn bản — Excel: =$Q*'Danh muc'!$O
        $officialSalary = $coreHours * $hourlyRate;
        $salary->official_salary = $officialSalary;

        // T: Số ngày nghỉ — Excel: =' Bảng nhập công'!DE+DF
        $totalAbsentDays = $daysLeaveAllowed + $daysLeaveNotAllowed;
        $salary->number_of_hours_worked = $totalAbsentDays;

        // U: Chuyên cần — LOGIC PHỨC TẠP
        // Excel: =IF(OR(DE>=3,DF>=2),0,IF(OR(DE=2,DF=1),(Q/2),Q))
        $diligenceAllowance = $this->calculateDiligence(
            $diligenceRate,
            $daysLeaveAllowed,
            $daysLeaveNotAllowed
        );
        $salary->allowance_diligence_detail = $diligenceAllowance;

        // W,X: Chuyên môn (expertise) — Ghi chú: cột Y trong Danh mục = Chuyên môn
        // Dùng trực tiếp giá trị đã nhập (không cần formula)

        // Z,AA: Trách nhiệm — Excel: =AB-(AB/26*T)
        // Trách nhiệm trừ theo ngày nghỉ
        $responsibilityAllowance = $this->calculateResponsibility(
            $responsibilityRate,
            $totalAbsentDays,
            $standardDays
        );
        $salary->number_of_jobs = $totalAbsentDays;
        $salary->allowance_responsibility_detail = $responsibilityAllowance;

        // AC: Số giờ tăng ca — Excel: =IF(E>'$Q$5',(E-$Q$5),0)+G
        // Giờ tăng ca = giờ vượt max + giờ TC thực tế
        $overtimeHoursDetail = max($totalDayHours - $maxHours, 0) + $totalOvertimeHours;
        $salary->overtime_hours_detail = $overtimeHoursDetail;

        // AD: Lương tăng ca — Excel: =AC*'Danh muc'!P
        $overtimeSalary = $overtimeHoursDetail * $otRate;
        $salary->overtime_salary = $overtimeSalary;

        // ================== C. PHỤ CẤP (Cột AF→BN) ==================

        // AF,AG: Phụ cấp cơm ca ngày — Excel: =K*'Danh muc'!V
        $salary->number_of_work_days = $riceDayCount;
        $riceAllowance = $riceDayCount * $allowanceRice;
        $salary->allowance_rice_detail = $riceAllowance;

        // AI,AJ: Phụ cấp ca đêm — Excel: =L*'Danh muc'!U
        $salary->number_of_work_nights = $nightShiftCount;
        $nightAllowanceDetail = $nightShiftCount * $allowanceNight;
        $salary->allowance_shift_night = $nightAllowanceDetail;

        // AL,AM: Phụ cấp tăng ca — Excel: =M*'Danh muc'!T
        $salary->overtime_day_count_detail = $otDayCount;
        $otAllowanceDetail = $otDayCount * $allowanceOvertimePerDay;
        $salary->allowance_overtime_detail = $otAllowanceDetail;

        // AO,AP: Tiền lễ tết — Excel: =AO*'Danh muc'!O*8
        $salary->holidays_count_detail = $holidaysCount;
        $holidayPay = $holidaysCount * $hourlyRate * $hoursPerDay;
        $salary->holidays_money = $holidayPay;

        // AR,AS: Tiền phép năm — Excel: =AR*'Danh muc'!O*8
        $salary->paid_holidays_count_detail = $paidLeaveCount;
        $paidLeavePay = $paidLeaveCount * $hourlyRate * $hoursPerDay;
        $salary->paid_holidays_money = $paidLeavePay;

        // AU,AV,AW: Lương đi công tác — Excel: =AU*AV
        $travelHours = (float)($salary->business_travel_hours ?? 0);
        $travelRate = (float)($salary->business_travel_unit_price_hour ?? 0);
        $travelSalary = $travelHours * $travelRate;
        $salary->gcn_business_travel_salary = $travelSalary;

        // AY,AZ,BA: Phụ cấp xăng công tác — Excel: =AY*AZ
        $trips = (float)($salary->number_of_business_trips ?? 0);
        $fuelRate = (float)($salary->business_fuel_unit_price_day ?? 0);
        $travelFuel = $trips * $fuelRate;
        $salary->allowance_gcn_business_fuel = $travelFuel;

        // BC-BN: Các khoản bổ sung (đã nhập trực tiếp)
        $referralMoney = (float)($salary->money_referral_people ?? 0);
        $otherAllowance = (float)($salary->allowance_diffrent ?? 0);
        $attendanceBonus = (float)($salary->bonuses_for_attendance ?? 0);
        $sicknessAllowance = (float)($salary->sickness ?? 0);
        $funeralAllowance = (float)($salary->funeral ?? 0);
        $birthdayMoney = (float)($salary->birthday_money ?? 0);
        $previousDebt = (float)($salary->previous_period_debt ?? 0);

        // ================== D. TỔNG HỢP (Cột BO→CF) ==================

        // BO: Tổng thu nhập
        // Excel: =F+I+L+O+R+U+AA+AD+AG+AJ+AM+AP+AS+AW+BA+BC+BE+BG+BI+BK+BM
        $totalIncome = $trialDaySalary          // F: Lương ca ngày thử việc
            + $trialNightSalary                 // I: Lương ca đêm thử việc
            + $trialOtSalary                    // L: Lương TC thử việc
            + $apprenticeAllowance              // O: Phụ cấp học việc
            + $officialSalary                   // R: Lương căn bản
            + $diligenceAllowance               // U: Chuyên cần
            + $responsibilityAllowance          // AA: Trách nhiệm
            + $overtimeSalary                   // AD: Lương tăng ca
            + $riceAllowance                    // AG: Phụ cấp cơm
            + $nightAllowanceDetail             // AJ: Phụ cấp ca đêm
            + $otAllowanceDetail                // AM: Phụ cấp tăng ca
            + $holidayPay                       // AP: Tiền lễ tết
            + $paidLeavePay                     // AS: Tiền phép năm
            + $travelSalary                     // AW: Lương công tác
            + $travelFuel                       // BA: Xăng công tác
            + $referralMoney                    // BC: Tiền giới thiệu
            + $otherAllowance                   // BE: Phụ cấp khác
            + $attendanceBonus                  // BG: Thưởng chuyên cần
            + $sicknessAllowance                // Ốm đau
            + $funeralAllowance                 // Ma chay
            + $birthdayMoney                    // BK: Sinh nhật
            + $previousDebt;                    // BM: Lương tháng trước thiếu
        $salary->total_income = $totalIncome;

        // BP: Khấu trừ BHXH 10.5% — Excel: ='Danh muc'!$X
        $insuranceDeduction = (float)($salary->insurance ?? 0);
        $salary->insurance_detail = $insuranceDeduction;

        // BR: Tạm ứng (đã nhập)
        $advanceMoney = (float)($salary->advance_money ?? 0);

        // BT: Phí công đoàn — Excel: ='Danh muc'!N*0.5%
        $unionFee = $salaryBasic * $this->config->union_fee_rate;

        // BV,BX: Số nghỉ có/không phép
        $salary->daysleave_allowed = $daysLeaveAllowed;
        $salary->daysleave_notallowed = $daysLeaveNotAllowed;

        // BZ-CD: KPI & Lỗi
        $kpiDeduction = $this->calculateKPIDeduction($salary);
        $salary->kpi_subtraction = $kpiDeduction;

        // CF: Thực lãnh — Excel: =FLOOR((BO-BP-BR-BT-CD),1000)
        $actuallyReceived = floor(
            ($totalIncome - $insuranceDeduction - $advanceMoney - $unionFee - $kpiDeduction) / $roundingUnit
        ) * $roundingUnit;
        $salary->actually_received = $actuallyReceived;

        // CH: BHXH CTY đóng — Excel: =IF(Y8="";0;(N8*21,5%))
        // Y8 = Khấu trừ BHXH NV ($insuranceDeduction), N8 = Lương CB ($salaryBasic)
        $companyInsuranceRate = $this->config->insurance_company_rate ?? 0.2150;
        $companyInsurance = $insuranceDeduction > 0 ? $salaryBasic * $companyInsuranceRate : 0;
        $salary->company_insurance_detail = $companyInsurance;

        // ===== CẬP NHẬT BẢNG LƯƠNG (Bang Thanh Toan Luong) =====
        $salary->salary_total = $totalIncome;
        $salary->insurance_payroll = $insuranceDeduction;
        $salary->advance_money_payroll = $advanceMoney;
        $salary->company_insurance_payroll = $companyInsurance;
        $salary->KPI_Subtraction_payroll = $kpiDeduction;
        $salary->previous_period_debt_payroll = $previousDebt;
        $salary->actually_received_payroll = $actuallyReceived;

        $salary->save();

        // Trả về kết quả chi tiết cho FE
        return $this->formatCalculationResult($salary);
    }

    /**
     * Tính lương cho tất cả nhân viên hoặc danh sách chọn
     */
    public function calculateAll(int $salaryManagerId, ?array $employeeIds = null, string $defaultType = 'worker'): array
    {
        $model = $this->getOfficialModel();
        $query = $model::where('salaries_manager_id', $salaryManagerId);

        if ($employeeIds) {
            $query->whereIn('employee_id', $employeeIds);
        }

        $salaries = $query->with('employee.role')->get();
        $results = [];
        $grandTotal = [
            'total_income' => 0,
            'total_deductions' => 0,
            'total_net_pay' => 0,
            'total_company_insurance' => 0,
            'count' => 0,
        ];

        foreach ($salaries as $salary) {
            try {
                // Ưu tiên dùng loại nhân viên đã chỉnh tay
                $type = $salary->employee_type ?: $this->detectEmployeeType($salary->employee);
                $result = $this->calculateForEmployee($salaryManagerId, $salary->employee_id, $type);
                $results[] = $result;

                $grandTotal['total_income'] += $result['summary']['total_income'];
                $grandTotal['total_deductions'] += $result['summary']['total_deductions'];
                $grandTotal['total_net_pay'] += $result['summary']['actually_received'];
                $grandTotal['total_company_insurance'] += $result['summary']['company_insurance'];
                $grandTotal['count']++;
            } catch (\Exception $e) {
                Log::error("Calculate salary error for employee {$salary->employee_id}: " . $e->getMessage());
                $results[] = [
                    'employee_id' => $salary->employee_id,
                    'error' => $e->getMessage(),
                ];
            }
        }

        // Cập nhật tổng lương vào SalaryManager
        $this->updateSalaryManagerTotal($salaryManagerId);

        return [
            'results' => $results,
            'grand_total' => $grandTotal,
        ];
    }

    /**
     * FE-driven mode:
     * FE tự tính toàn bộ rồi gửi payload lên, BE chỉ map vào DB.
     */
    public function mapCalculatedForEmployee(
        int $salaryManagerId,
        string $employeeId,
        array $payload,
        bool $updateManagerTotal = true
    ): array {
        $mappedFields = [];
        $mappedTimekeepingRows = null;

        DB::transaction(function () use (
            $salaryManagerId,
            $employeeId,
            $payload,
            &$mappedFields,
            &$mappedTimekeepingRows
        ) {
            $salary = $this->findOrCreateSalaryRecord($salaryManagerId, $employeeId);
            $salary->loadMissing('employee.role');

            $updateData = [];

            if (array_key_exists('employee_type', $payload)) {
                $updateData['employee_type'] = $payload['employee_type'];
            }

            if (array_key_exists('timekeeping_summary', $payload) && is_array($payload['timekeeping_summary'])) {
                $updateData = array_merge(
                    $updateData,
                    $this->mapTimekeepingSummaryFromFrontend($payload['timekeeping_summary'])
                );
            }

            $calculationDetail = [];
            if (array_key_exists('calculation_detail', $payload) && is_array($payload['calculation_detail'])) {
                $calculationDetail = $payload['calculation_detail'];
                $updateData = array_merge(
                    $updateData,
                    $this->mapCalculationDetailFromFrontend($calculationDetail)
                );
            }

            $summarySection = is_array($calculationDetail['summary'] ?? null)
                ? $calculationDetail['summary']
                : [];
            $allowanceSection = is_array($calculationDetail['allowance_section'] ?? null)
                ? $calculationDetail['allowance_section']
                : [];
            $payrollPayload = is_array($payload['payroll'] ?? null)
                ? $payload['payroll']
                : [];

            $updateData = array_merge(
                $updateData,
                $this->mapPayrollFromFrontend($payrollPayload, $summarySection, $allowanceSection)
            );

            // Cho phép FE gửi flat data để map thẳng theo key DB.
            if (array_key_exists('salary_fields', $payload) && is_array($payload['salary_fields'])) {
                $updateData = array_merge($updateData, $payload['salary_fields']);
            }
            if (array_key_exists('sheet_all_columns', $payload) && is_array($payload['sheet_all_columns'])) {
                $updateData = array_merge($updateData, $payload['sheet_all_columns']);
            }

            $filtered = $this->filterSalaryFillable($salary, $updateData);
            if (! empty($filtered)) {
                $salary->fill($filtered);
                $salary->save();
            }
            $mappedFields = array_keys($filtered);

            if (array_key_exists('timekeeping_daily', $payload) && is_array($payload['timekeeping_daily'])) {
                $mappedTimekeepingRows = $this->replaceTimekeepingRowsFromFrontend($salary->id, $payload['timekeeping_daily']);
            }
        });

        if ($updateManagerTotal) {
            $this->updateSalaryManagerTotal($salaryManagerId);
        }

        $salary = $this->findOrCreateSalaryRecord($salaryManagerId, $employeeId);
        $salary->load('employee.role');

        return [
            'employee_id' => $salary->employee_id,
            'mapped_fields' => $mappedFields,
            'mapped_timekeeping_rows' => $mappedTimekeepingRows,
            'calculation_detail' => $this->formatCalculationResult($salary),
            'payroll' => [
                'salary_total' => $salary->salary_total,
                'insurance_payroll' => $salary->insurance_payroll,
                'advance_money_payroll' => $salary->advance_money_payroll,
                'company_insurance_payroll' => $salary->company_insurance_payroll,
                'KPI_Subtraction_payroll' => $salary->KPI_Subtraction_payroll,
                'previous_period_debt_payroll' => $salary->previous_period_debt_payroll,
                'actually_received_payroll' => $salary->actually_received_payroll,
            ],
        ];
    }

    /**
     * FE-driven bulk mapping.
     */
    public function mapCalculatedBulk(int $salaryManagerId, array $employeesPayload): array
    {
        $results = [];
        $mappedCount = 0;
        $errorCount = 0;

        foreach ($employeesPayload as $row) {
            $employeeId = isset($row['employee_id']) ? (string)$row['employee_id'] : '';
            if ($employeeId === '') {
                $errorCount++;
                $results[] = [
                    'employee_id' => null,
                    'error' => 'employee_id is required in calculated_data item',
                ];
                continue;
            }

            try {
                $results[] = $this->mapCalculatedForEmployee(
                    $salaryManagerId,
                    $employeeId,
                    $row,
                    false
                );
                $mappedCount++;
            } catch (\Throwable $e) {
                $errorCount++;
                Log::error("Map FE calculated salary error for employee {$employeeId}: ".$e->getMessage());
                $results[] = [
                    'employee_id' => $employeeId,
                    'error' => $e->getMessage(),
                ];
            }
        }

        $this->updateSalaryManagerTotal($salaryManagerId);

        return [
            'results' => $results,
            'mapped_count' => $mappedCount,
            'error_count' => $errorCount,
        ];
    }

    // ========================================================================
    // STEP 6: LẤY DỮ LIỆU BẢNG LƯƠNG (cho FE hiển thị)
    // ========================================================================

    /**
     * Lấy toàn bộ dữ liệu bảng lương cho FE (tất cả tabs)
     */
    public function getSalaryData(int $salaryManagerId): array
    {
        $model = $this->getOfficialModel();
        $timekeepingRelation = $this->company === 'a7a'
            ? 'SalaryOfficialA7ATimekeepings'
            : 'SalaryOfficialVVPTimekeepings';

        $salaries = $model::where('salaries_manager_id', $salaryManagerId)
            ->with([
                'employee:id,name,role_id,company',
                'employee.role:id,role_name',
                $timekeepingRelation => function($q) {
                    $q->orderBy('timekeeping_date', 'asc');
                },
            ])
            ->get();

        $data = [];
        foreach ($salaries as $salary) {
            $timekeepingDaily = $salary->$timekeepingRelation->map(function ($t) {
                return [
                    'date' => $t->timekeeping_date,
                    'day_hours' => $t->timekeeping_day,
                    'night_hours' => $t->timekeeping_night,
                    'overtime_hours' => $t->timekeeping_overtime,
                ];
            })->values();

            $calculationDetail = $this->formatCalculationResult($salary);
            $unionFee = ((float)($salary->salary_basic ?? 0)) * ((float)($this->config->union_fee_rate ?? 0));

            $data[] = [
                'id' => $salary->id,
                'employee_id' => $salary->employee_id,
                'employee_name' => $salary->employee->name ?? '',
                'department' => $salary->employee->role->role_name ?? '',
                'employee_type' => $salary->employee_type ?: $this->detectEmployeeType($salary->employee),

                // Danh mục (Category)
                'category' => [
                    'salary_day' => ($salary->salary_basic > 0) ? 0 : $salary->salary_day,
                    'salary_night' => ($salary->salary_basic > 0) ? 0 : $salary->salary_night,
                    'probationary_salary_basic_26days' => ($salary->salary_basic > 0) ? 0 : $salary->probationary_salary_basic_26days,
                    'probationary_salary_basic_hours' => ($salary->salary_basic > 0) ? 0 : $salary->probationary_salary_basic_hours,
                    'probationary_salary_basic_extra_hours' => ($salary->salary_basic > 0) ? 0 : $salary->probationary_salary_basic_extra_hours,
                    'allowance_apprentice' => $salary->allowance_apprentice,
                    'salary_basic' => $salary->salary_basic,
                    'regular_salary_hour' => $salary->regular_salary_hour,
                    'salary_overtime' => $salary->salary_overtime,
                    'allowance_diligence' => $salary->allowance_diligence,
                    'allowance_responsibility' => $salary->allowance_responsibility,
                    'allowance_overtime' => $salary->allowance_overtime,
                    'allowance_night' => $salary->allowance_night,
                    'allowance_rice' => $salary->allowance_rice,
                    'company_insurance' => $salary->company_insurance,
                    'insurance' => $salary->insurance,
                    'has_insurance' => (bool)$salary->has_insurance,
                ],

                // Chấm công summary
                'timekeeping_summary' => [
                    'total_day_hours' => (float)($salary->total_day_offical ?? 0),
                    'total_night_hours' => (float)($salary->total_night_offical ?? 0),
                    'total_overtime_hours' => (float)($salary->total_overtime_offical ?? 0),
                    'trial_day_count' => (float)($salary->workday_count_trial ?? 0),
                    'trial_night_count' => (float)($salary->worknight_count_trial ?? 0),
                    'trial_overtime_count' => (float)($salary->overtime_day_count_trial ?? 0),
                    'rice_day_count' => (float)($salary->allowance_rice_day_timekeeping ?? 0),
                    'night_shift_count' => (float)($salary->allowance_rice_night_timekeeping ?? 0),
                    'overtime_day_count' => (float)($salary->allowance_overtime_timekeeping ?? 0),
                    'holidays_count' => (float)($salary->holidays_count ?? 0),
                    'paid_holidays_count' => (float)($salary->paid_holidays_count ?? 0),
                    'days_leave_allowed' => (float)($salary->daysleave_allowed_timekeeping ?? 0),
                    'days_leave_not_allowed' => (float)($salary->daysleave_notallowed_timekeeping ?? 0),
                ],

                // Chấm công chi tiết hàng ngày
                'timekeeping_daily' => $timekeepingDaily,

                // Chi tiết tính toán (Bảng tính toán)
                'calculation_detail' => $calculationDetail,

                // Bảng lương tóm tắt
                'payroll' => [
                    'salary_total' => $salary->salary_total,
                    'insurance_payroll' => $salary->insurance_payroll,
                    'advance_money_payroll' => $salary->advance_money_payroll,
                    'company_insurance_payroll' => $salary->company_insurance_payroll,
                    'KPI_Subtraction_payroll' => $salary->KPI_Subtraction_payroll,
                    'previous_period_debt_payroll' => $salary->previous_period_debt_payroll,
                    'actually_received_payroll' => $salary->actually_received_payroll,
                ],

                // Full raw columns để FE dễ map theo các tab/sheet cũ
                'sheet_all_columns' => $this->extractSalaryColumns($salary),

                // Sheet: Danh mục
                'sheet_danh_muc' => [
                    'excel_codes' => [
                        'H' => $salary->salary_day,
                        'I' => $salary->salary_night,
                        'J' => $salary->probationary_salary_basic_26days,
                        'K' => $salary->probationary_salary_basic_hours,
                        'L' => $salary->probationary_salary_basic_extra_hours,
                        'M' => $salary->allowance_apprentice,
                        'N' => $salary->salary_basic,
                        'O' => $salary->regular_salary_hour,
                        'P' => $salary->salary_overtime,
                        'Q' => $salary->allowance_diligence,
                        'R' => $salary->allowance_responsibility,
                        'T' => $salary->allowance_overtime,
                        'U' => $salary->allowance_night,
                        'V' => $salary->allowance_rice,
                        'W' => $salary->company_insurance,
                        'X' => $salary->insurance,
                        'Y' => $salary->has_insurance,
                    ],
                    'data' => [
                        'salary_day' => ($salary->salary_basic > 0) ? 0 : $salary->salary_day,
                        'salary_night' => ($salary->salary_basic > 0) ? 0 : $salary->salary_night,
                        'probationary_salary_basic_26days' => ($salary->salary_basic > 0) ? 0 : $salary->probationary_salary_basic_26days,
                        'probationary_salary_basic_hours' => ($salary->salary_basic > 0) ? 0 : $salary->probationary_salary_basic_hours,
                        'probationary_salary_basic_extra_hours' => ($salary->salary_basic > 0) ? 0 : $salary->probationary_salary_basic_extra_hours,
                        'allowance_apprentice' => $salary->allowance_apprentice,
                        'salary_basic' => $salary->salary_basic,
                        'regular_salary_hour' => $salary->regular_salary_hour,
                        'salary_overtime' => $salary->salary_overtime,
                        'allowance_diligence' => $salary->allowance_diligence,
                        'allowance_responsibility' => $salary->allowance_responsibility,
                        'allowance_overtime' => $salary->allowance_overtime,
                        'allowance_night' => $salary->allowance_night,
                        'allowance_rice' => $salary->allowance_rice,
                        'company_insurance' => $salary->company_insurance,
                        'insurance' => $salary->insurance,
                        'has_insurance' => (bool)$salary->has_insurance,
                    ],
                ],

                // Sheet: Bảng nhập công
                'sheet_bang_nhap_cong' => [
                    'excel_codes' => [
                        'E' => (float)($salary->total_day_offical ?? 0),
                        'F' => (float)($salary->total_night_offical ?? 0),
                        'G' => (float)($salary->total_overtime_offical ?? 0),
                        'H' => (float)($salary->workday_count_trial ?? 0),
                        'I' => (float)($salary->worknight_count_trial ?? 0),
                        'J' => (float)($salary->overtime_day_count_trial ?? 0),
                        'K' => (float)($salary->allowance_rice_day_timekeeping ?? 0),
                        'L' => (float)($salary->allowance_rice_night_timekeeping ?? 0),
                        'M' => (float)($salary->allowance_overtime_timekeeping ?? 0),
                        'DC' => (float)($salary->holidays_count ?? 0),
                        'DD' => (float)($salary->paid_holidays_count ?? 0),
                        'DE' => (float)($salary->daysleave_allowed_timekeeping ?? 0),
                        'DF' => (float)($salary->daysleave_notallowed_timekeeping ?? 0),
                    ],
                    'summary' => $this->getTimekeepingSummary($salary),
                    'daily' => $timekeepingDaily,
                ],

                // Sheet: Bảng tính toán
                'sheet_bang_tinh_toan' => [
                    'excel_codes' => [
                        'F' => $salary->day_shift_salary_trial,
                        'I' => $salary->night_shift_salary_trial,
                        'L' => $salary->overtime_salary_trial,
                        'O' => $salary->allowance_apprentice_detail,
                        'Q' => $salary->core_hours,
                        'R' => $salary->official_salary,
                        'T' => $salary->number_of_hours_worked,
                        'U' => $salary->allowance_diligence_detail,
                        'AA' => $salary->allowance_responsibility_detail,
                        'AC' => $salary->overtime_hours_detail,
                        'AD' => $salary->overtime_salary,
                        'AG' => $salary->allowance_rice_detail,
                        'AJ' => $salary->allowance_shift_night,
                        'AM' => $salary->allowance_overtime_detail,
                        'AP' => $salary->holidays_money,
                        'AS' => $salary->paid_holidays_money,
                        'AW' => $salary->gcn_business_travel_salary,
                        'BA' => $salary->allowance_gcn_business_fuel,
                        'BM' => $salary->previous_period_debt,
                        'BO' => $salary->total_income,
                        'BP' => $salary->insurance_detail,
                        'BR' => $salary->advance_money,
                        'BT' => $unionFee,
                        'CD' => $salary->kpi_subtraction,
                        'CF' => $salary->actually_received,
                        'CH' => $salary->company_insurance_detail,
                    ],
                    'detail' => $calculationDetail,
                ],

                // Sheet: Bảng thanh toán lương
                'sheet_bang_thanh_toan_luong' => [
                    'excel_codes' => [
                        'BO' => $salary->salary_total,
                        'BP' => $salary->insurance_payroll,
                        'BT' => $unionFee,
                        'BR' => $salary->advance_money_payroll,
                        'CD' => $salary->KPI_Subtraction_payroll,
                        'BM' => $salary->previous_period_debt_payroll,
                        'CF' => $salary->actually_received_payroll,
                        'CH' => $salary->company_insurance_payroll,
                    ],
                    'summary' => [
                        'tong_luong' => $salary->salary_total,
                        'khau_tru_bhxh' => $salary->insurance_payroll,
                        'phi_cong_doan' => $unionFee,
                        'tam_ung' => $salary->advance_money_payroll,
                        'kpi' => $salary->KPI_Subtraction_payroll,
                        'no_ky_truoc' => $salary->previous_period_debt_payroll,
                        'thuc_lanh' => $salary->actually_received_payroll,
                    ],
                ],
            ];
        }

        return $data;
    }

    /**
     * Trả profile công thức để FE hiển thị trace/tooltip theo rule BE.
     */
    public function getFormulaProfiles(): array
    {
        return [
            'profile_name' => 'default_'.$this->company.'_v1',
            'company' => $this->company,
            'standard_hours_cap' => [
                'worker' => (float)$this->config->max_hours_worker,
                'office' => (float)$this->config->max_hours_office,
            ],
            'timekeeping_rules' => [
                'day_hours_summary' => 'sum(day_hours > 1)',
                'night_hours_summary' => 'sum(night_hours > 1)',
                'overtime_hours_summary' => 'sum(overtime_hours)',
                'trial_day_count' => 'sum(day_hours > 0 && day_hours <= 1)',
                'trial_night_count' => 'sum(night_hours > 0 && night_hours <= 1)',
                'rice_day_count' => 'count(day_hours >= 5)',
                'night_shift_count' => 'count(night_hours >= 8)',
                'overtime_day_count' => 'count(day_hours >= 8)',
            ],
            'salary_rules' => [
                'core_hours_policy' => 'min(total_day_hours + total_night_hours, standard_hours_cap)',
                'overtime_hours_policy' => 'max((total_day_hours + total_night_hours) - standard_hours_cap, 0) + total_overtime_hours',
                'diligence_policy' => 'if(DE>=3 or DF>=2)->0; if(DE==2 or DF==1)->50%; else 100%',
                'responsibility_policy' => 'allowance_responsibility - (allowance_responsibility / standard_work_days * absent_days)',
                'rounding_policy' => 'floor(value / rounding_unit) * rounding_unit',
            ],
            'rounding' => [
                'unit' => (int)$this->config->rounding_unit,
                'mode' => 'floor',
            ],
        ];
    }

    /**
     * Meta phục vụ FE theo dõi phiên bản tính toán.
     */
    public function getCalculationMeta(int $salaryManagerId): array
    {
        $model = $this->getOfficialModel();
        $latestUpdatedAt = $model::where('salaries_manager_id', $salaryManagerId)->max('updated_at');

        return [
            'version' => $latestUpdatedAt ? Carbon::parse($latestUpdatedAt)->timestamp : null,
            'calculated_at' => $latestUpdatedAt ? Carbon::parse($latestUpdatedAt)->toIso8601String() : null,
            'formula_profile' => 'default_'.$this->company.'_v1',
            'rounding_unit' => (int)$this->config->rounding_unit,
            'company' => $this->company,
        ];
    }

    /**
     * Lấy cấu trúc formulas để FE hiểu và hiển thị
     * FE có thể dùng để hiển thị tooltip giải thích công thức cho user
     */
    public function getFormulaDefinitions(): array
    {
        return [
            'category_derived' => [
                'regular_salary_hour' => [
                    'label' => 'Lương CB/giờ',
                    'formula' => 'salary_basic / ' . $this->config->hourly_divisor,
                    'excel_ref' => '=N/204',
                ],
                'salary_overtime' => [
                    'label' => 'Lương TC/giờ',
                    'formula' => 'regular_salary_hour × ' . $this->config->overtime_multiplier,
                    'excel_ref' => '=O×1.5',
                ],
                'company_insurance' => [
                    'label' => 'BHXH CTY đóng',
                    'formula' => 'salary_basic × ' . ($this->config->insurance_company_rate * 100) . '%',
                    'excel_ref' => '=N×21.5%',
                ],
                'insurance' => [
                    'label' => 'BHXH NLĐ đóng',
                    'formula' => 'salary_basic × ' . ($this->config->insurance_employee_rate * 100) . '%',
                    'excel_ref' => '=N×10.5%',
                ],
            ],
            'timekeeping_formulas' => [
                'total_day_hours' => [
                    'label' => 'Tổng giờ ngày',
                    'formula' => 'SUM(giờ ngày > 1 qua tất cả ngày)',
                    'excel_ref' => '=IF(N>1,N,0)+IF(Q>1,Q,0)+...',
                ],
                'rice_day_count' => [
                    'label' => 'Số ngày ăn cơm',
                    'formula' => 'Đếm ngày có giờ ngày >= 5',
                    'excel_ref' => '=IF(N>=5,1,0)+...',
                ],
                'night_shift_count' => [
                    'label' => 'Số ca đêm',
                    'formula' => 'Đếm đêm có giờ >= 8',
                    'excel_ref' => '=IF(O>=8,1,0)+...',
                ],
            ],
            'calculation_formulas' => [
                'core_hours' => [
                    'label' => 'Số giờ chính',
                    'formula' => 'MIN(tổng_giờ_ngày + tổng_giờ_đêm, max_hours)',
                    'excel_ref' => '=IF((E+F)>=Q$5,Q$5,(E+F))',
                    'description' => 'Giới hạn giờ làm chính thức, vượt quá tính tăng ca',
                ],
                'official_salary' => [
                    'label' => 'Lương căn bản',
                    'formula' => 'core_hours × regular_salary_hour',
                    'excel_ref' => '=Q×O',
                ],
                'diligence_allowance' => [
                    'label' => 'Chuyên cần',
                    'formula' => 'IF(nghỉ_CP>=3 OR nghỉ_KP>=2, 0, IF(nghỉ_CP=2 OR nghỉ_KP=1, 50%, 100%))',
                    'excel_ref' => '=IF(OR(DE>=3,DF>=2),0,IF(OR(DE=2,DF=1),Q/2,Q))',
                    'description' => 'Nghỉ nhiều thì mất chuyên cần: >=3 có phép hoặc >=2 không phép → mất hết',
                ],
                'responsibility_allowance' => [
                    'label' => 'Trách nhiệm',
                    'formula' => 'responsibility_rate - (responsibility_rate / 26 × absent_days)',
                    'excel_ref' => '=AB-(AB/26×T)',
                    'description' => 'Trách nhiệm trừ tỷ lệ theo số ngày nghỉ',
                ],
                'overtime_hours' => [
                    'label' => 'Số giờ tăng ca',
                    'formula' => 'MAX(giờ_ngày - max_hours, 0) + giờ_tăng_ca',
                    'excel_ref' => '=IF(E>Q$5,(E-Q$5),0)+G',
                ],
                'overtime_salary' => [
                    'label' => 'Lương tăng ca',
                    'formula' => 'overtime_hours × salary_overtime',
                    'excel_ref' => '=AC×P',
                ],
                'holiday_pay' => [
                    'label' => 'Tiền lễ tết',
                    'formula' => 'holidays_count × regular_salary_hour × 8',
                    'excel_ref' => '=AO×O×8',
                ],
                'paid_leave_pay' => [
                    'label' => 'Tiền phép năm',
                    'formula' => 'paid_leave_count × regular_salary_hour × 8',
                    'excel_ref' => '=AR×O×8',
                ],
                'total_income' => [
                    'label' => 'Tổng thu nhập',
                    'formula' => 'SUM(tất cả thu nhập)',
                    'excel_ref' => '=F+I+L+O+R+U+AA+AD+AG+AJ+AM+AP+AS+AW+BA+BC+BE+BG+BI+BK+BM',
                ],
                'union_fee' => [
                    'label' => 'Phí công đoàn',
                    'formula' => 'salary_basic × 0.5%',
                    'excel_ref' => '=N×0.5%',
                ],
                'actually_received' => [
                    'label' => 'Thực lãnh',
                    'formula' => 'FLOOR(tổng_thu_nhập - BHXH - tạm_ứng - phí_CĐ - KPI, 1000)',
                    'excel_ref' => '=FLOOR((BO-BP-BR-BT-CD),1000)',
                    'description' => 'Làm tròn xuống đến hàng nghìn',
                ],
            ],
        ];
    }

    /**
     * Map summary timekeeping FE -> DB columns.
     */
    private function mapTimekeepingSummaryFromFrontend(array $summary): array
    {
        $mapped = [];
        $this->mapFields($summary, $mapped, [
            'total_day_hours' => 'total_day_offical',
            'total_night_hours' => 'total_night_offical',
            'total_overtime_hours' => 'total_overtime_offical',
            'trial_day_count' => 'workday_count_trial',
            'trial_night_count' => 'worknight_count_trial',
            'trial_overtime_count' => 'overtime_day_count_trial',
            'overtime_day_count_trial' => 'overtime_day_count_trial',
            'rice_day_count' => 'allowance_rice_day_timekeeping',
            'night_shift_count' => 'allowance_rice_night_timekeeping',
            'overtime_day_count' => 'allowance_overtime_timekeeping',
            'holidays_count' => 'holidays_count',
            'paid_holidays_count' => 'paid_holidays_count',
            'days_leave_allowed' => 'daysleave_allowed_timekeeping',
            'days_leave_not_allowed' => 'daysleave_notallowed_timekeeping',
            'daysleave_allowed_timekeeping' => 'daysleave_allowed_timekeeping',
            'daysleave_notallowed_timekeeping' => 'daysleave_notallowed_timekeeping',
        ]);

        return $mapped;
    }

    /**
     * Map calculation detail FE -> DB columns.
     */
    private function mapCalculationDetailFromFrontend(array $detail): array
    {
        $mapped = [];

        $trial = is_array($detail['trial_section'] ?? null) ? $detail['trial_section'] : [];
        $official = is_array($detail['official_section'] ?? null) ? $detail['official_section'] : [];
        $allowance = is_array($detail['allowance_section'] ?? null) ? $detail['allowance_section'] : [];
        $summary = is_array($detail['summary'] ?? null) ? $detail['summary'] : [];
        $kpi = is_array($detail['kpi_detail'] ?? null) ? $detail['kpi_detail'] : [];

        $this->mapFields($trial, $mapped, [
            'trial_day_count' => 'number_of_work_days_trial',
            'trial_day_salary' => 'day_shift_salary_trial',
            'trial_day_salary_notice' => 'day_shift_salary_trial_notice',
            'trial_night_count' => 'number_of_work_nights_trial',
            'trial_night_salary' => 'night_shift_salary_trial',
            'trial_night_salary_notice' => 'night_shift_salary_trial_notice',
            'trial_overtime_hours' => 'overtime_hours_trial',
            'trial_overtime_salary' => 'overtime_salary_trial',
            'trial_overtime_salary_notice' => 'overtime_salary_trial_notice',
            'apprentice_days' => 'number_of_work',
            'apprentice_allowance' => 'allowance_apprentice_detail',
            'apprentice_allowance_notice' => 'allowance_apprentice_detail_notice',
        ]);

        $this->mapFields($official, $mapped, [
            'core_hours' => 'core_hours',
            'official_salary' => 'official_salary',
            'official_salary_notice' => 'official_salary_notice',
            'base_salary_notice' => 'official_salary_notice',
            'absent_days' => 'number_of_hours_worked',
            'diligence_allowance' => 'allowance_diligence_detail',
            'diligence_allowance_notice' => 'allowance_diligence_detail_notice',
            'responsibility_allowance' => 'allowance_responsibility_detail',
            'responsibility_allowance_notice' => 'allowance_responsibility_detail_notice',
            'overtime_hours' => 'overtime_hours_detail',
            'overtime_salary' => 'overtime_salary',
            'overtime_salary_notice' => 'overtime_salary_notice',
        ]);

        $this->mapFields($allowance, $mapped, [
            'rice_days' => 'number_of_work_days',
            'rice_allowance' => 'allowance_rice_detail',
            'night_shift_count' => 'number_of_work_nights',
            'night_allowance' => 'allowance_shift_night',
            'overtime_day_count' => 'overtime_day_count_detail',
            'overtime_allowance' => 'allowance_overtime_detail',
            'overtime_allowance_notice' => 'allowance_overtime_detail_notice',
            'holiday_count' => 'holidays_count_detail',
            'holiday_pay' => 'holidays_money',
            'holiday_pay_notice' => 'holidays_money_notice',
            'paid_leave_count' => 'paid_holidays_count_detail',
            'paid_leave_pay' => 'paid_holidays_money',
            'paid_leave_pay_notice' => 'paid_holidays_money_notice',
            'travel_hours' => 'business_travel_hours',
            'travel_rate' => 'business_travel_unit_price_hour',
            'travel_salary' => 'gcn_business_travel_salary',
            'travel_trips' => 'number_of_business_trips',
            'travel_fuel_rate' => 'business_fuel_unit_price_day',
            'travel_fuel' => 'allowance_gcn_business_fuel',
            'referral_money' => 'money_referral_people',
            'other_allowance' => 'allowance_diffrent',
            'other_allowance_notice' => 'allowance_diffrent_notice',
            'attendance_bonus' => 'bonuses_for_attendance',
            'attendance_bonus_notice' => 'bonuses_for_attendance_notice',
            'sickness' => 'sickness',
            'sickness_notice' => 'sickness_notice',
            'funeral' => 'funeral',
            'funeral_notice' => 'funeral_notice',
            'birthday_money' => 'birthday_money',
            'birthday_money_notice' => 'birthday_money_notice',
            'previous_debt' => 'previous_period_debt',
            'previous_debt_notice' => 'previous_period_debt_notice',
        ]);

        $this->mapFields($summary, $mapped, [
            'total_income' => 'total_income',
            'insurance_deduction' => 'insurance_detail',
            'insurance_deduction_notice' => 'insurance_detail_notice',
            'advance_money' => 'advance_money',
            'advance_money_notice' => 'advance_money_notice',
            'union_fee_notice' => 'unicon_deduction_notice',
            'kpi_deduction' => 'kpi_subtraction',
            'kpi_deduction_notice' => 'kpi_subtraction_notice',
            'actually_received' => 'actually_received',
            'company_insurance' => 'company_insurance_detail',
            'forms_of_payment' => 'forms_of_payment',
        ]);

        $this->mapFields($kpi, $mapped, [
            'days_leave_allowed' => 'daysleave_allowed',
            'days_leave_allowed_notice' => 'subtract_daysleave_allowed_notice',
            'days_leave_not_allowed' => 'daysleave_notallowed',
            'days_leave_not_allowed_notice' => 'subtract_daysleave_notallowed_notice',
            'error_serious' => 'error_serious',
            'error_serious_notice' => 'subtract_error_serious_notice',
            'error_minor' => 'error_minor',
            'error_minor_notice' => 'subtract_error_minor_notice',
            'kpi_deduction' => 'kpi_subtraction',
        ]);

        return $mapped;
    }

    /**
     * Map payroll summary FE -> DB columns.
     */
    private function mapPayrollFromFrontend(array $payroll, array $summarySection = [], array $allowanceSection = []): array
    {
        $mapped = [];
        $this->mapFields($payroll, $mapped, [
            'salary_total' => 'salary_total',
            'insurance_payroll' => 'insurance_payroll',
            'advance_money_payroll' => 'advance_money_payroll',
            'company_insurance_payroll' => 'company_insurance_payroll',
            'KPI_Subtraction_payroll' => 'KPI_Subtraction_payroll',
            'previous_period_debt_payroll' => 'previous_period_debt_payroll',
            'actually_received_payroll' => 'actually_received_payroll',
        ]);

        if (! array_key_exists('salary_total', $mapped) && array_key_exists('total_income', $summarySection)) {
            $mapped['salary_total'] = $summarySection['total_income'];
        }
        if (! array_key_exists('insurance_payroll', $mapped) && array_key_exists('insurance_deduction', $summarySection)) {
            $mapped['insurance_payroll'] = $summarySection['insurance_deduction'];
        }
        if (! array_key_exists('advance_money_payroll', $mapped) && array_key_exists('advance_money', $summarySection)) {
            $mapped['advance_money_payroll'] = $summarySection['advance_money'];
        }
        if (! array_key_exists('company_insurance_payroll', $mapped) && array_key_exists('company_insurance', $summarySection)) {
            $mapped['company_insurance_payroll'] = $summarySection['company_insurance'];
        }
        if (! array_key_exists('KPI_Subtraction_payroll', $mapped) && array_key_exists('kpi_deduction', $summarySection)) {
            $mapped['KPI_Subtraction_payroll'] = $summarySection['kpi_deduction'];
        }
        if (! array_key_exists('previous_period_debt_payroll', $mapped) && array_key_exists('previous_debt', $allowanceSection)) {
            $mapped['previous_period_debt_payroll'] = $allowanceSection['previous_debt'];
        }
        if (! array_key_exists('actually_received_payroll', $mapped) && array_key_exists('actually_received', $summarySection)) {
            $mapped['actually_received_payroll'] = $summarySection['actually_received'];
        }

        return $mapped;
    }

    /**
     * Replace all daily timekeeping rows by FE provided values.
     */
    private function replaceTimekeepingRowsFromFrontend(int $salaryId, array $timekeepingDaily): int
    {
        $timekeepingModel = $this->getTimekeepingModel();
        $foreignKey = $this->getTimekeepingForeignKey();

        $timekeepingModel::where($foreignKey, $salaryId)->delete();

        $rowsByDate = [];
        $now = now();

        foreach ($timekeepingDaily as $item) {
            if (! is_array($item) || empty($item['date'])) {
                continue;
            }

            $date = Carbon::parse($item['date'])->format('Y-m-d');
            $rowsByDate[$date] = [
                $foreignKey => $salaryId,
                'timekeeping_date' => $date,
                'timekeeping_day' => $item['day_hours'] ?? null,
                'timekeeping_night' => $item['night_hours'] ?? null,
                'timekeeping_overtime' => $item['overtime_hours'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $rows = array_values($rowsByDate);
        if (! empty($rows)) {
            foreach (array_chunk($rows, 200) as $chunk) {
                $timekeepingModel::insert($chunk);
            }
        }

        return count($rows);
    }

    /**
     * Map keys từ section source -> DB fields target.
     */
    private function mapFields(array $source, array &$target, array $fieldMap): void
    {
        foreach ($fieldMap as $sourceKey => $targetKey) {
            if (array_key_exists($sourceKey, $source)) {
                $target[$targetKey] = $source[$sourceKey];
            }
        }
    }

    /**
     * Chỉ giữ field có trong fillable và chặn field khóa.
     */
    private function filterSalaryFillable($salary, array $data): array
    {
        $fillable = array_flip($salary->getFillable());
        $blocked = [
            'salaries_manager_id',
            'employee_id',
        ];

        $filtered = [];
        foreach ($data as $field => $value) {
            if (in_array($field, $blocked, true)) {
                continue;
            }

            if (array_key_exists($field, $fillable)) {
                $filtered[$field] = $value;
            }
        }

        return $filtered;
    }

    // ========================================================================
    // PRIVATE HELPERS — Logic tính toán chi tiết theo Excel
    // ========================================================================

    /**
     * Tính chuyên cần — KHỚP CHÍNH XÁC với Excel
     *
     * Excel: =IF(OR(DE>=3,DF>=2),0,IF(OR(DE=2,DF=1),(Q/2),Q))
     *
     * Quy tắc (từ "Phụ lục tính lương"):
     * - Nghỉ 1 ngày có phép → Không trừ chuyên cần
     * - Nghỉ 2 ngày có phép → Trừ 50% chuyên cần
     * - Nghỉ 3 ngày có phép trở lên → Trừ hết chuyên cần
     * - Nghỉ 1 ngày không phép → Trừ 50% chuyên cần
     * - Nghỉ 2 ngày không phép trở lên → Trừ hết chuyên cần
     */
    private function calculateDiligence(float $diligenceRate, float $daysLeaveAllowed, float $daysLeaveNotAllowed): float
    {
        if ($daysLeaveAllowed >= 3 || $daysLeaveNotAllowed >= 2) {
            return 0; // Mất hết chuyên cần
        }

        if ($daysLeaveAllowed == 2 || $daysLeaveNotAllowed == 1) {
            return $diligenceRate / 2; // Trừ 50% chuyên cần
        }

        return $diligenceRate; // Hưởng đủ chuyên cần
    }

    /**
     * Tính trách nhiệm — KHỚP CHÍNH XÁC với Excel
     *
     * Excel: =AB-(AB/26*T)
     * T = số ngày nghỉ (có phép + không phép)
     *
     * Trách nhiệm bị trừ tỷ lệ theo số ngày nghỉ
     */
    private function calculateResponsibility(float $responsibilityRate, float $absentDays, int $standardDays): float
    {
        if ($responsibilityRate <= 0) {
            return 0;
        }

        return $responsibilityRate - ($responsibilityRate / $standardDays * $absentDays);
    }

    /**
     * Tính KPI trừ
     *
     * Quy tắc (từ "Phụ lục tính lương"):
     * - 3 lỗi nặng → trừ 200,000đ
     * - 3 lỗi nặng + 1 → trừ 400,000đ
     * - Thêm 1 lỗi nặng → trừ thêm 200,000đ
     * - 2 lỗi nhẹ = 1 lỗi nặng
     * - Trừ tối thiểu đến 5tr không trừ nữa
     */
    private function calculateKPIDeduction($salary): float
    {
        $errorSerious = (float)($salary->error_serious ?? 0);
        $errorMinor = (float)($salary->error_minor ?? 0);

        // 2 lỗi nhẹ = 1 lỗi nặng
        $totalSeriousErrors = $errorSerious + floor($errorMinor / 2);

        if ($totalSeriousErrors < 3) {
            return 0;
        }

        // 3 lỗi nặng → 200,000đ, mỗi lỗi thêm → +200,000đ
        $deduction = ($totalSeriousErrors - 2) * 200000;

        // Giới hạn trừ tối đa (trừ xuống còn 5tr → total_income - 5,000,000)
        $totalIncome = (float)($salary->total_income ?? 0);
        $maxDeduction = max($totalIncome - 5000000, 0);

        return min($deduction, $maxDeduction);
    }

    /**
     * Format kết quả tính lương cho FE hiển thị
     */
    private function formatCalculationResult($salary): array
    {
        $salaryBasic = (float)($salary->salary_basic ?? 0);
        $unionFee = $salaryBasic * $this->config->union_fee_rate;
        $insuranceDeduction = (float)($salary->insurance_detail ?? $salary->insurance ?? 0);
        $advanceMoney = (float)($salary->advance_money ?? 0);
        $kpiDeduction = (float)($salary->kpi_subtraction ?? 0);

        return [
            'employee_id' => $salary->employee_id,
            'employee_name' => $salary->employee->name ?? '',
            'department' => $salary->employee->role->role_name ?? '',

            'trial_section' => [
                'trial_day_count' => $salary->number_of_work_days_trial,
                'trial_day_salary' => $salary->day_shift_salary_trial,
                'trial_day_salary_notice' => $salary->day_shift_salary_trial_notice,
                'trial_night_count' => $salary->number_of_work_nights_trial,
                'trial_night_salary' => $salary->night_shift_salary_trial,
                'trial_night_salary_notice' => $salary->night_shift_salary_trial_notice,
                'trial_overtime_hours' => $salary->overtime_hours_trial,
                'trial_overtime_salary' => $salary->overtime_salary_trial,
                'trial_overtime_salary_notice' => $salary->overtime_salary_trial_notice,
                'apprentice_days' => $salary->number_of_work,
                'apprentice_allowance' => $salary->allowance_apprentice_detail,
                'apprentice_allowance_notice' => $salary->allowance_apprentice_detail_notice,
            ],

            'official_section' => [
                'core_hours' => $salary->core_hours,
                'official_salary' => $salary->official_salary,
                'official_salary_notice' => $salary->official_salary_notice,
                'salary_basic_monthly' => $salaryBasic,
                'regular_salary_hour' => $salary->regular_salary_hour,
                'salary_overtime_rate' => $salary->salary_overtime,
                'absent_days' => $salary->number_of_hours_worked,
                'diligence_allowance' => $salary->allowance_diligence_detail,
                'diligence_allowance_notice' => $salary->allowance_diligence_detail_notice,
                'responsibility_allowance' => $salary->allowance_responsibility_detail,
                'responsibility_allowance_notice' => $salary->allowance_responsibility_detail_notice,
                'overtime_hours' => $salary->overtime_hours_detail,
                'overtime_salary' => $salary->overtime_salary,
                'overtime_salary_notice' => $salary->overtime_salary_notice,
            ],

            'allowance_section' => [
                'rice_days' => $salary->number_of_work_days,
                'rice_allowance' => $salary->allowance_rice_detail,
                'night_shift_count' => $salary->number_of_work_nights,
                'night_allowance' => $salary->allowance_shift_night,
                'overtime_day_count' => $salary->overtime_day_count_detail,
                'overtime_allowance' => $salary->allowance_overtime_detail,
                'overtime_allowance_notice' => $salary->allowance_overtime_detail_notice,
                'holiday_count' => $salary->holidays_count_detail,
                'holiday_pay' => $salary->holidays_money,
                'holiday_pay_notice' => $salary->holidays_money_notice,
                'paid_leave_count' => $salary->paid_holidays_count_detail,
                'paid_leave_pay' => $salary->paid_holidays_money,
                'paid_leave_pay_notice' => $salary->paid_holidays_money_notice,
                'travel_hours' => $salary->business_travel_hours,
                'travel_rate' => $salary->business_travel_unit_price_hour,
                'travel_salary' => $salary->gcn_business_travel_salary,
                'travel_trips' => $salary->number_of_business_trips,
                'travel_fuel_rate' => $salary->business_fuel_unit_price_day,
                'travel_fuel' => $salary->allowance_gcn_business_fuel,
                'referral_money' => $salary->money_referral_people,
                'other_allowance' => $salary->allowance_diffrent,
                'other_allowance_notice' => $salary->allowance_diffrent_notice,
                'attendance_bonus' => $salary->bonuses_for_attendance,
                'attendance_bonus_notice' => $salary->bonuses_for_attendance_notice,
                'sickness' => $salary->sickness,
                'funeral' => $salary->funeral,
                'birthday_money' => $salary->birthday_money,
                'previous_debt' => $salary->previous_period_debt,
                'previous_debt_notice' => $salary->previous_period_debt_notice,
            ],

            'summary' => [
                'total_income' => $salary->total_income,
                'insurance_deduction' => $insuranceDeduction,
                'advance_money' => $advanceMoney,
                'advance_money_notice' => $salary->advance_money_notice,
                'union_fee' => $unionFee,
                'union_fee_notice' => $salary->unicon_deduction_notice,
                'kpi_deduction' => $kpiDeduction,
                'kpi_deduction_notice' => $salary->kpi_subtraction_notice,
                'total_deductions' => $insuranceDeduction + $advanceMoney + $unionFee + $kpiDeduction,
                'actually_received' => $salary->actually_received,
                'company_insurance' => $salary->company_insurance_detail,
                'forms_of_payment' => $salary->forms_of_payment ?? 'Chuyển khoản',
            ],

            'kpi_detail' => [
                'days_leave_allowed' => $salary->daysleave_allowed,
                'days_leave_not_allowed' => $salary->daysleave_notallowed,
                'error_serious' => $salary->error_serious,
                'error_serious_notice' => $salary->subtract_error_serious_notice,
                'error_minor' => $salary->error_minor,
                'error_minor_notice' => $salary->subtract_error_minor_notice,
                'kpi_deduction' => $kpiDeduction,
            ],
        ];
    }

    /**
     * Tự động xác định loại nhân viên dựa vào bộ phận
     */
    private function detectEmployeeType(?Employee $employee): string
    {
        if (!$employee || !$employee->role) {
            return 'worker';
        }

        $officeDepartments = ['Giám đốc', 'P. Giám đốc', 'IT', 'QC', 'HC', 'VP', 'Kế toán', 'Nhân sự'];
        $roleName = $employee->role->role_name ?? '';

        foreach ($officeDepartments as $dept) {
            if (stripos($roleName, $dept) !== false) {
                return 'office';
            }
        }

        return 'worker';
    }

    /**
     * Cập nhật tổng lương vào SalaryManager
     */
    private function updateSalaryManagerTotal(int $salaryManagerId): void
    {
        $total = 0;

        if ($this->company === 'vvp' || $this->company === 'all') {
            $total += SalaryOfficialVVP::where('salaries_manager_id', $salaryManagerId)
                ->sum('actually_received_payroll');
        }

        if ($this->company === 'a7a' || $this->company === 'all') {
            $total += SalaryOfficialA7A::where('salaries_manager_id', $salaryManagerId)
                ->sum('actually_received_payroll');
        }

        SalaryManager::where('id', $salaryManagerId)->update(['total' => $total]);
    }

    // ========================================================================
    // MODEL HELPERS — Dynamic model selection dựa trên company
    // ========================================================================

    private function getOfficialModel(): string
    {
        return $this->company === 'a7a' ? SalaryOfficialA7A::class : SalaryOfficialVVP::class;
    }

    private function getTimekeepingModel(): string
    {
        return $this->company === 'a7a'
            ? SalaryOfficialA7ATimekeeping::class
            : SalaryOfficialVVPTimekeeping::class;
    }

    private function getTimekeepingForeignKey(): string
    {
        return $this->company === 'a7a' ? 'salary_official_a7a_id' : 'salary_official_vvp_id';
    }
}
