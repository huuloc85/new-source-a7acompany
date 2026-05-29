<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach ($this->columnCommentsByTable() as $table => $comments) {
            $this->applyColumnComments($table, $comments);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach ($this->columnCommentsByTable() as $table => $comments) {
            $this->applyColumnComments($table, array_fill_keys(array_keys($comments), ''));
        }
    }

    private function applyColumnComments(string $table, array $comments): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        $columns = collect(DB::select(
            'select column_name, column_type, is_nullable, column_default, extra from information_schema.columns where table_schema = ? and table_name = ?',
            [DB::getDatabaseName(), $table]
        ))->keyBy('column_name');

        foreach ($comments as $column => $comment) {
            if (! $columns->has($column)) {
                continue;
            }

            $metadata = $columns->get($column);
            $definition = $this->columnDefinition($metadata);
            $quotedComment = DB::getPdo()->quote($comment);

            DB::statement("alter table `{$table}` modify column `{$column}` {$definition} comment {$quotedComment}");
        }
    }

    private function columnDefinition(object $metadata): string
    {
        $definition = $metadata->column_type;
        $definition .= $metadata->is_nullable === 'YES' ? ' null' : ' not null';

        if ($metadata->column_default !== null) {
            $definition .= ' default '.$this->defaultExpression($metadata->column_default);
        }

        if ($metadata->extra !== '') {
            $definition .= ' '.$metadata->extra;
        }

        return $definition;
    }

    private function defaultExpression(string $default): string
    {
        $upperDefault = strtoupper($default);

        if ($upperDefault === 'NULL') {
            return 'null';
        }

        if (in_array($upperDefault, ['CURRENT_TIMESTAMP', 'CURRENT_TIMESTAMP()'], true)) {
            return $default;
        }

        return DB::getPdo()->quote($default);
    }

    private function columnCommentsByTable(): array
    {
        return [
            'salary_officials_a7a' => $this->salaryOfficialComments('A7A', [
                'subtract_of_violations' => '[Sheet: Bảng tính toán][A7A] Trừ vi phạm',
                'subtract_of_violations_notice' => '[Sheet: Bảng tính toán][A7A] Ghi chú trừ vi phạm',
            ]),
            'salary_officials_vvp' => $this->salaryOfficialComments('VVP', [
                'unicon_deduction' => '[Sheet: Bảng tính toán][VVP] Phí công đoàn / khoản khấu trừ',
                'unicon_deduction_notice' => '[Sheet: Bảng tính toán][VVP] Ghi chú phí công đoàn / khoản khấu trừ',
            ]),
        ];
    }

    private function salaryOfficialComments(string $company, array $violationColumns): array
    {
        return array_merge([
            'id' => "[Hệ thống][{$company}] Khóa chính",
            'salaries_manager_id' => "[Sheet: Thông tin bảng lương][{$company}] Bảng lương",
            'employee_id' => "[Sheet: Danh muc][{$company}] Mã nhân viên",

            'salary_day' => "[Sheet: Danh muc][{$company}] Lương ngày",
            'salary_night' => "[Sheet: Danh muc][{$company}] Lương đêm",
            'probationary_salary_basic_26days' => "[Sheet: Danh muc][{$company}] Lương CB thử việc / 26 ngày",
            'probationary_salary_basic_hours' => "[Sheet: Danh muc][{$company}] Lương CB thử việc / 1 giờ",
            'probationary_salary_basic_extra_hours' => "[Sheet: Danh muc][{$company}] Lương CB thử việc tăng ca / 1 giờ",
            'allowance_apprentice' => "[Sheet: Danh muc][{$company}] Phụ cấp học việc",
            'salary_basic' => "[Sheet: Danh muc][{$company}] Lương CB chính thức / 26 ngày",
            'regular_salary_hour' => "[Sheet: Danh muc][{$company}] Lương CB / giờ",
            'salary_overtime' => "[Sheet: Danh muc][{$company}] Lương tăng ca / giờ",
            'allowance_diligence' => "[Sheet: Danh muc][{$company}] Phụ cấp chuyên cần",
            'allowance_responsibility' => "[Sheet: Danh muc][{$company}] Phụ cấp trách nhiệm",
            'allowance_overtime' => "[Sheet: Danh muc][{$company}] Phụ cấp tăng ca / ngày",
            'allowance_night' => "[Sheet: Danh muc][{$company}] Phụ cấp đêm",
            'allowance_rice' => "[Sheet: Danh muc][{$company}] Phụ cấp cơm",
            'company_insurance' => "[Sheet: Danh muc][{$company}] BHXH công ty đóng",
            'insurance' => "[Sheet: Danh muc][{$company}] BHXH người lao động đóng",

            'total_day_offical' => "[Sheet: Bảng nhập công][{$company}] Tổng ngày",
            'total_night_offical' => "[Sheet: Bảng nhập công][{$company}] Tổng đêm",
            'total_overtime_offical' => "[Sheet: Bảng nhập công][{$company}] Tổng tăng ca",
            'workday_count_trial' => "[Sheet: Bảng nhập công][{$company}] Số công ngày",
            'workday_count_trail' => "[Sheet: Bảng nhập công][{$company}] Số công ngày",
            'worknight_count_trial' => "[Sheet: Bảng nhập công][{$company}] Số công đêm",
            'overtime_day_count_trial' => "[Sheet: Bảng nhập công][{$company}] Số ngày tăng ca",
            'allowance_rice_day_timekeeping' => "[Sheet: Bảng nhập công][{$company}] Phụ cấp cơm ngày",
            'allowance_rice_night_timekeeping' => "[Sheet: Bảng nhập công][{$company}] Phụ cấp cơm đêm",
            'allowance_overtime_timekeeping' => "[Sheet: Bảng nhập công][{$company}] Phụ cấp tăng ca",
            'holidays_count' => "[Sheet: Bảng nhập công][{$company}] Số ngày lễ tết",
            'paid_holidays_count' => "[Sheet: Bảng nhập công][{$company}] Số ngày phép năm",
            'daysleave_allowed_timekeeping' => "[Sheet: Bảng nhập công][{$company}] Số ngày nghỉ có phép",
            'daysleave_notallowed_timekeeping' => "[Sheet: Bảng nhập công][{$company}] Số ngày nghỉ không phép",

            'number_of_work_days_trial' => "[Sheet: Bảng tính toán][{$company}] Số công ngày thử việc",
            'day_shift_salary_trial' => "[Sheet: Bảng tính toán][{$company}] Lương ca ngày thử việc",
            'day_shift_salary_trial_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú lương ca ngày thử việc",
            'number_of_work_nights_trial' => "[Sheet: Bảng tính toán][{$company}] Số công đêm thử việc",
            'night_shift_salary_trial' => "[Sheet: Bảng tính toán][{$company}] Lương ca đêm thử việc",
            'night_shift_salary_trial_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú lương ca đêm thử việc",
            'overtime_hours_trial' => "[Sheet: Bảng tính toán][{$company}] Số giờ tăng ca thử việc",
            'overtime_salary_trial' => "[Sheet: Bảng tính toán][{$company}] Lương tăng ca thử việc",
            'overtime_salary_trial_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú lương tăng ca thử việc",
            'number_of_work' => "[Sheet: Bảng tính toán][{$company}] Số công",
            'allowance_apprentice_detail' => "[Sheet: Bảng tính toán][{$company}] Phụ cấp học việc",
            'allowance_apprentice_detail_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú phụ cấp học việc",
            'core_hours' => "[Sheet: Bảng tính toán][{$company}] Số giờ chính",
            'official_salary' => "[Sheet: Bảng tính toán][{$company}] Lương căn bản",
            'official_salary_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú lương căn bản",
            'number_of_hours_worked' => "[Sheet: Bảng tính toán][{$company}] Số công làm",
            'allowance_diligence_detail' => "[Sheet: Bảng tính toán][{$company}] Chuyên cần",
            'allowance_diligence_detail_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú chuyên cần",
            'allowance_professional_detail' => "[Sheet: Bảng tính toán][{$company}] Chuyên môn",
            'allowance_professional_detail_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú chuyên môn",
            'number_of_jobs' => "[Sheet: Bảng tính toán][{$company}] Số công làm",
            'allowance_responsibility_detail' => "[Sheet: Bảng tính toán][{$company}] Trách nhiệm",
            'allowance_responsibility_detail_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú trách nhiệm",
            'overtime_hours_detail' => "[Sheet: Bảng tính toán][{$company}] Số giờ tăng ca",
            'overtime_salary' => "[Sheet: Bảng tính toán][{$company}] Lương tăng ca",
            'overtime_salary_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú lương tăng ca",
            'reinforcement_hours_detail' => "[Sheet: Bảng tính toán][{$company}] Số giờ tăng cường",
            'reinforcement_salary' => "[Sheet: Bảng tính toán][{$company}] Lương tăng cường",
            'reinforcement_salary_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú lương tăng cường",
            'number_of_work_days' => "[Sheet: Bảng tính toán][{$company}] Số công ngày",
            'allowance_rice_detail' => "[Sheet: Bảng tính toán][{$company}] Phụ cấp cơm ca ngày",
            'allowance_rice_detail_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú phụ cấp cơm ca ngày",
            'number_of_work_nights' => "[Sheet: Bảng tính toán][{$company}] Số công đêm",
            'allowance_shift_night' => "[Sheet: Bảng tính toán][{$company}] Phụ cấp ca đêm",
            'allowance_shift_night_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú phụ cấp ca đêm",
            'overtime_day_count_detail' => "[Sheet: Bảng tính toán][{$company}] Số ngày tăng ca",
            'allowance_overtime_detail' => "[Sheet: Bảng tính toán][{$company}] Phụ cấp tăng ca",
            'allowance_overtime_detail_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú phụ cấp tăng ca",
            'holidays_count_detail' => "[Sheet: Bảng tính toán][{$company}] Số ngày lễ tết",
            'holidays_money' => "[Sheet: Bảng tính toán][{$company}] Tiền lễ tết",
            'holidays_money_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú tiền lễ tết",
            'paid_holidays_count_detail' => "[Sheet: Bảng tính toán][{$company}] Phép năm",
            'paid_holidays_money' => "[Sheet: Bảng tính toán][{$company}] Tiền phép năm",
            'paid_holidays_money_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú tiền phép năm",
            'business_travel_hours' => "[Sheet: Bảng tính toán][{$company}] Số giờ đi công tác",
            'business_travel_unit_price_hour' => "[Sheet: Bảng tính toán][{$company}] Đơn giá đi công tác / giờ",
            'gcn_business_travel_salary' => "[Sheet: Bảng tính toán][{$company}] Lương đi công tác GCN",
            'gcn_business_travel_salary_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú lương đi công tác GCN",
            'number_of_business_trips' => "[Sheet: Bảng tính toán][{$company}] Số lần đi công tác",
            'business_fuel_unit_price_day' => "[Sheet: Bảng tính toán][{$company}] Đơn giá xăng công tác / ngày",
            'allowance_gcn_business_fuel' => "[Sheet: Bảng tính toán][{$company}] Phụ cấp xăng đi GCN",
            'allowance_gcn_business_fuel_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú phụ cấp xăng đi GCN",
            'money_referral_people' => "[Sheet: Bảng tính toán][{$company}] Tiền giới thiệu người",
            'money_referral_people_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú tiền giới thiệu người",
            'allowance_diffrent' => "[Sheet: Bảng tính toán][{$company}] Phụ cấp khác",
            'allowance_diffrent_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú phụ cấp khác",
            'bonuses_for_attendance' => "[Sheet: Bảng tính toán][{$company}] Tiền thưởng đạt chuyên cần",
            'bonuses_for_attendance_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú tiền thưởng đạt chuyên cần",
            'previous_month_kpi_refund' => "[Sheet: Bảng tính toán][{$company}] Hoàn tiền KPI tháng trước",
            'previous_month_kpi_refund_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú hoàn tiền KPI tháng trước",
            'sickness' => "[Sheet: Bảng tính toán][{$company}] Ốm đau",
            'sickness_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú ốm đau",
            'funeral' => "[Sheet: Bảng tính toán][{$company}] Ma chay",
            'funeral_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú ma chay",
            'birthday_money' => "[Sheet: Bảng tính toán][{$company}] Tiền sinh nhật",
            'birthday_money_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú tiền sinh nhật",
            'previous_period_debt' => "[Sheet: Bảng tính toán][{$company}] Tiền lương tháng trước bị thiếu",
            'previous_period_debt_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú tiền lương tháng trước bị thiếu",
            'total_income' => "[Sheet: Bảng tính toán][{$company}] Tổng thu nhập",
            'insurance_detail' => "[Sheet: Bảng tính toán][{$company}] Khấu trừ BHXH 10.5%",
            'insurance_detail_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú khấu trừ BHXH 10.5%",
            'advance_money' => "[Sheet: Bảng tính toán][{$company}] Tạm ứng",
            'advance_money_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú tạm ứng",
            'union_fee' => "[Sheet: Bảng tính toán][{$company}] Phí công đoàn 0.5%",
            'union_fee_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú phí công đoàn 0.5%",
            'number_of_violations' => "[Sheet: Bảng tính toán][{$company}] Số lần vi phạm",
            'daysleave_allowed' => "[Sheet: Bảng tính toán][{$company}] Số nghỉ có phép",
            'daysleave_allowed_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú số nghỉ có phép",
            'subtract_daysleave_allowed' => "[Sheet: Bảng tính toán][{$company}] Trừ tiền nghỉ có phép",
            'subtract_daysleave_allowed_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú trừ tiền nghỉ có phép",
            'daysleave_notallowed' => "[Sheet: Bảng tính toán][{$company}] Số nghỉ không phép",
            'daysleave_notallowed_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú số nghỉ không phép",
            'subtract_daysleave_notallowed' => "[Sheet: Bảng tính toán][{$company}] Trừ tiền nghỉ không phép",
            'subtract_daysleave_notallowed_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú trừ tiền nghỉ không phép",
            'error_serious' => "[Sheet: Bảng tính toán][{$company}] Số lỗi nặng",
            'error_serious_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú số lỗi nặng",
            'subtract_error_serious' => "[Sheet: Bảng tính toán][{$company}] Trừ tiền số lỗi nặng",
            'subtract_error_serious_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú trừ tiền số lỗi nặng",
            'error_minor' => "[Sheet: Bảng tính toán][{$company}] Số lỗi nhẹ",
            'error_minor_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú số lỗi nhẹ",
            'subtract_error_minor' => "[Sheet: Bảng tính toán][{$company}] Trừ tiền số lỗi nhẹ",
            'subtract_error_minor_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú trừ tiền số lỗi nhẹ",
            'kpi_subtraction' => "[Sheet: Bảng tính toán][{$company}] Bị trừ KPI tháng này",
            'kpi_subtraction_notice' => "[Sheet: Bảng tính toán][{$company}] Ghi chú bị trừ KPI tháng này",
            'actually_received' => "[Sheet: Bảng tính toán][{$company}] Thực lãnh",
            'forms_of_payment' => "[Sheet: Bảng tính toán][{$company}] Hình thức thanh toán",
            'company_insurance_detail' => "[Sheet: Bảng tính toán][{$company}] BHXH 21.5% công ty đóng cho NLĐ",

            'salary_total' => "[Sheet: Bang Thanh Toan Luong][{$company}] Tổng lương",
            'insurance_payroll' => "[Sheet: Bang Thanh Toan Luong][{$company}] Trừ bảo hiểm",
            'advance_money_payroll' => "[Sheet: Bang Thanh Toan Luong][{$company}] Tạm ứng",
            'company_insurance_payroll' => "[Sheet: Bang Thanh Toan Luong][{$company}] Bảo hiểm công ty đóng",
            'KPI_Subtraction_payroll' => "[Sheet: Bang Thanh Toan Luong][{$company}] Trừ KPI",
            'previous_period_debt_payroll' => "[Sheet: Bang Thanh Toan Luong][{$company}] Nợ kỳ trước",
            'actually_received_payroll' => "[Sheet: Bang Thanh Toan Luong][{$company}] Thực lãnh",

            'created_at' => "[Hệ thống][{$company}] Ngày tạo",
            'updated_at' => "[Hệ thống][{$company}] Ngày cập nhật",
            'deleted_at' => "[Hệ thống][{$company}] Ngày xóa mềm",
        ], $violationColumns);
    }
};
