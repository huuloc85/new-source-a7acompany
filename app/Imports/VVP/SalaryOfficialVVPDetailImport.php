<?php

namespace App\Imports\VVP;

use App\Helpers\LogHelper;
use App\Imports\Traits\OptimizesSalaryImport;
use App\Models\SalaryOfficialVVP;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Validators\Failure;

class SalaryOfficialVVPDetailImport implements HasReferencesToOtherSheets, SkipsEmptyRows, SkipsOnFailure, ToArray, WithCalculatedFormulas, WithChunkReading, WithEvents, WithStartRow, WithValidation
{
    use OptimizesSalaryImport;

    private const DETAIL_HEADER_ROW = 7;

    public $roleIgnore;

    public $salaryManagerId;

    private $employeeMap = [];

    private $salaryMap = [];

    private $headerColumns = [];

    private $headersByColumn = [];

    public function __construct($salaryManagerId)
    {
        $this->salaryManagerId = $salaryManagerId;
        $this->roleIgnore = [6, 9, 12, 15, 18, 21, 24, 27, 30, 33, 36, 39, 42, 46, 50, 52, 54, 56, 58, 60, 62, 64, 67, 69, 72, 75, 78, 81, 84, 87];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function sheet(): string
    {
        return 'Bảng tính toán'; // Đặt tên sheet ở đây
    }

    public function array(array $rows)
    {
        try {
            // Preload data once before processing
            if (empty($this->employeeMap)) {
                $this->employeeMap = $this->getPreloadedEmployees();
            }
            if (empty($this->salaryMap) && $this->salaryManagerId) {
                $this->salaryMap = $this->getPreloadedSalaryRecords(SalaryOfficialVVP::class, $this->salaryManagerId);
            }

            $updates = [];
            $now = now();

            foreach ($rows as $row) {
                $employeeColumn = $this->headerColumn('Mã NV') ?? 1;
                if (($row[$employeeColumn] ?? null) != null && ($row[$employeeColumn] ?? null) != '') {
                    $employee = $this->getEmployeeFast($row[$employeeColumn], $this->employeeMap);
                    if ($employee != null && $this->salaryManagerId != null) {
                        $salaryManager = $this->getSalaryRecordFast($this->salaryMap, $employee->id);
                        if ($salaryManager) {
                            $this->fillDetailAttributes($salaryManager, $row);
                            $salaryManager->salary_total = $salaryManager->total_income;
                            $salaryManager->insurance_payroll = $salaryManager->insurance_detail;
                            $salaryManager->advance_money_payroll = $salaryManager->advance_money;
                            $salaryManager->company_insurance_payroll = $salaryManager->company_insurance_detail;
                            $salaryManager->KPI_Subtraction_payroll = $salaryManager->kpi_subtraction;
                            $salaryManager->previous_period_debt_payroll = $salaryManager->previous_period_debt;
                            $salaryManager->actually_received_payroll = $salaryManager->actually_received;

                            $attributes = $salaryManager->getAttributes();
                            $attributes['updated_at'] = $now;
                            $updates[] = $attributes;
                        }
                    }
                }
            }

            $this->batchUpsertByIdUsingRecordColumns(SalaryOfficialVVP::class, $updates, 100);
        } catch (\Exception $e) {
            LogHelper::saveLog('Import-Detail-VVP', $e->getMessage(), $e->getLine());
            Log::error('errors detail:: '.$e->getMessage().' getLine'.$e->getLine());
            throw $e;
        }
    }

    public function startRow(): int
    {
        return 8; // Start importing from row 8
    }

    private function fillDetailAttributes(SalaryOfficialVVP $salaryManager, array $row): void
    {
        foreach ($this->detailFields() as $field) {
            $value = $this->valueForField($row, $field);
            $salaryManager->{$field['attribute']} = ($field['type'] ?? 'numeric') === 'string'
                ? $this->stringValue($value)
                : $this->numericValue($value);
        }
    }

    private function detailFields(): array
    {
        return [
            ['attribute' => 'number_of_work_days_trial', 'header' => 'Số công ngày', 'occurrence' => 1, 'fallback' => 4, 'type' => 'numeric'],
            ['attribute' => 'day_shift_salary_trial', 'header' => 'Lương ca ngày (thử việc)', 'fallback' => 5, 'type' => 'numeric'],
            ['attribute' => 'day_shift_salary_trial_notice', 'noticeAfter' => 'Lương ca ngày (thử việc)', 'fallback' => 6, 'type' => 'string'],
            ['attribute' => 'number_of_work_nights_trial', 'header' => 'Số công đêm', 'occurrence' => 1, 'fallback' => 7, 'type' => 'numeric'],
            ['attribute' => 'night_shift_salary_trial', 'header' => 'Lương ca đêm (thử việc)', 'fallback' => 8, 'type' => 'numeric'],
            ['attribute' => 'night_shift_salary_trial_notice', 'noticeAfter' => 'Lương ca đêm (thử việc)', 'fallback' => 9, 'type' => 'string'],
            ['attribute' => 'overtime_hours_trial', 'header' => 'Số giờ tăng ca ( thử việc)', 'fallback' => 10, 'type' => 'numeric'],
            ['attribute' => 'overtime_salary_trial', 'header' => 'Lương tăng ca (thử việc)', 'fallback' => 11, 'type' => 'numeric'],
            ['attribute' => 'overtime_salary_trial_notice', 'noticeAfter' => 'Lương tăng ca (thử việc)', 'fallback' => 12, 'type' => 'string'],
            ['attribute' => 'number_of_work', 'header' => 'Số công', 'fallback' => 13, 'type' => 'numeric'],
            ['attribute' => 'allowance_apprentice_detail', 'header' => 'Phụ cấp học việc', 'fallback' => 14, 'type' => 'numeric'],
            ['attribute' => 'allowance_apprentice_detail_notice', 'noticeAfter' => 'Phụ cấp học việc', 'fallback' => 15, 'type' => 'string'],
            ['attribute' => 'core_hours', 'header' => 'Số giờ chính', 'fallback' => 16, 'type' => 'numeric'],
            ['attribute' => 'official_salary', 'header' => 'Lương căn bản', 'fallback' => 17, 'type' => 'numeric'],
            ['attribute' => 'official_salary_notice', 'noticeAfter' => 'Lương căn bản', 'fallback' => 18, 'type' => 'string'],
            ['attribute' => 'number_of_hours_worked', 'header' => 'Số công làm', 'occurrence' => 1, 'fallback' => 19, 'type' => 'numeric'],
            ['attribute' => 'allowance_diligence_detail', 'header' => 'Chuyên cần', 'fallback' => 20, 'type' => 'numeric'],
            ['attribute' => 'allowance_diligence_detail_notice', 'noticeAfter' => 'Chuyên cần', 'fallback' => 21, 'type' => 'string'],
            ['attribute' => 'allowance_professional_detail', 'header' => 'Chuyên môn', 'fallback' => 23, 'type' => 'numeric'],
            ['attribute' => 'allowance_professional_detail_notice', 'noticeAfter' => 'Chuyên môn', 'fallback' => 24, 'type' => 'string'],
            ['attribute' => 'number_of_jobs', 'header' => 'Số công làm', 'occurrence' => 2, 'fallback' => 22, 'type' => 'numeric'],
            ['attribute' => 'allowance_responsibility_detail', 'header' => 'Trách nhiệm', 'fallback' => 23, 'type' => 'numeric'],
            ['attribute' => 'allowance_responsibility_detail_notice', 'noticeAfter' => 'Trách nhiệm', 'fallback' => 24, 'type' => 'string'],
            ['attribute' => 'overtime_hours_detail', 'header' => 'Số giờ tăng ca', 'fallback' => 25, 'type' => 'numeric'],
            ['attribute' => 'overtime_salary', 'header' => 'Lương tăng ca', 'fallback' => 26, 'type' => 'numeric'],
            ['attribute' => 'overtime_salary_notice', 'noticeAfter' => 'Lương tăng ca', 'fallback' => 27, 'type' => 'string'],
            ['attribute' => 'reinforcement_hours_detail', 'header' => 'Số giờ tăng cường', 'fallback' => 31, 'type' => 'numeric'],
            ['attribute' => 'reinforcement_salary', 'header' => 'Lương tăng cường', 'fallback' => 32, 'type' => 'numeric'],
            ['attribute' => 'reinforcement_salary_notice', 'noticeAfter' => 'Lương tăng cường', 'fallback' => 33, 'type' => 'string'],
            ['attribute' => 'number_of_work_days', 'header' => 'Số công ngày', 'occurrence' => 2, 'fallback' => 28, 'type' => 'numeric'],
            ['attribute' => 'allowance_rice_detail', 'header' => 'Phụ cấp cơm ca ngày', 'fallback' => 29, 'type' => 'numeric'],
            ['attribute' => 'allowance_rice_detail_notice', 'noticeAfter' => 'Phụ cấp cơm ca ngày', 'fallback' => 30, 'type' => 'string'],
            ['attribute' => 'number_of_work_nights', 'header' => 'Số công đêm', 'occurrence' => 2, 'fallback' => 31, 'type' => 'numeric'],
            ['attribute' => 'allowance_shift_night', 'header' => 'Phụ cấp ca đêm', 'fallback' => 32, 'type' => 'numeric'],
            ['attribute' => 'allowance_shift_night_notice', 'noticeAfter' => 'Phụ cấp ca đêm', 'fallback' => 33, 'type' => 'string'],
            ['attribute' => 'overtime_day_count_detail', 'header' => 'Số ngày tăng ca', 'fallback' => 34, 'type' => 'numeric'],
            ['attribute' => 'allowance_overtime_detail', 'header' => 'Phụ cấp tăng ca', 'fallback' => 35, 'type' => 'numeric'],
            ['attribute' => 'allowance_overtime_detail_notice', 'noticeAfter' => 'Phụ cấp tăng ca', 'fallback' => 36, 'type' => 'string'],
            ['attribute' => 'holidays_count_detail', 'header' => 'Số ngày lễ tết', 'fallback' => 37, 'type' => 'numeric'],
            ['attribute' => 'holidays_money', 'header' => 'Tiền lễ tết', 'fallback' => 38, 'type' => 'numeric'],
            ['attribute' => 'holidays_money_notice', 'noticeAfter' => 'Tiền lễ tết', 'fallback' => 39, 'type' => 'string'],
            ['attribute' => 'paid_holidays_count_detail', 'header' => 'Phép năm', 'fallback' => 40, 'type' => 'numeric'],
            ['attribute' => 'paid_holidays_money', 'header' => 'Tiền phép năm', 'fallback' => 41, 'type' => 'numeric'],
            ['attribute' => 'paid_holidays_money_notice', 'noticeAfter' => 'Tiền phép năm', 'fallback' => 42, 'type' => 'string'],
            ['attribute' => 'business_travel_hours', 'header' => 'Số giờ đi công tác', 'fallback' => 43, 'type' => 'numeric'],
            ['attribute' => 'business_travel_unit_price_hour', 'header' => 'Đơn giá đi công tác/ giờ', 'fallback' => 44, 'type' => 'numeric'],
            ['attribute' => 'gcn_business_travel_salary', 'header' => 'Lương đi công tác GCN', 'fallback' => 45, 'type' => 'numeric'],
            ['attribute' => 'gcn_business_travel_salary_notice', 'noticeAfter' => 'Lương đi công tác GCN', 'fallback' => 46, 'type' => 'string'],
            ['attribute' => 'number_of_business_trips', 'header' => 'Số lần đi công tác', 'fallback' => 47, 'type' => 'numeric'],
            ['attribute' => 'business_fuel_unit_price_day', 'header' => 'Đơn giá xăng công tác/ ngày', 'fallback' => 48, 'type' => 'numeric'],
            ['attribute' => 'allowance_gcn_business_fuel', 'header' => 'Phụ cấp xăng đi GCN', 'fallback' => 49, 'type' => 'numeric'],
            ['attribute' => 'allowance_gcn_business_fuel_notice', 'noticeAfter' => 'Phụ cấp xăng đi GCN', 'fallback' => 50, 'type' => 'string'],
            ['attribute' => 'money_referral_people', 'header' => 'Tiền giới thiệu người', 'fallback' => 51, 'type' => 'numeric'],
            ['attribute' => 'money_referral_people_notice', 'noticeAfter' => 'Tiền giới thiệu người', 'fallback' => 52, 'type' => 'string'],
            ['attribute' => 'allowance_diffrent', 'header' => 'Phụ cấp khác', 'fallback' => 53, 'type' => 'numeric'],
            ['attribute' => 'allowance_diffrent_notice', 'noticeAfter' => 'Phụ cấp khác', 'fallback' => 54, 'type' => 'string'],
            ['attribute' => 'bonuses_for_attendance', 'header' => 'Tiền thưởng đạt chuyên cần', 'fallback' => 55, 'type' => 'numeric'],
            ['attribute' => 'bonuses_for_attendance_notice', 'noticeAfter' => 'Tiền thưởng đạt chuyên cần', 'fallback' => 56, 'type' => 'string'],
            ['attribute' => 'previous_month_kpi_refund', 'header' => 'Hoàn tiền KPI tháng trước', 'fallback' => 63, 'type' => 'numeric'],
            ['attribute' => 'previous_month_kpi_refund_notice', 'noticeAfter' => 'Hoàn tiền KPI tháng trước', 'fallback' => 64, 'type' => 'string'],
            ['attribute' => 'sickness', 'header' => 'Ốm đau', 'fallback' => 57, 'type' => 'numeric'],
            ['attribute' => 'sickness_notice', 'noticeAfter' => 'Ốm đau', 'fallback' => 58, 'type' => 'string'],
            ['attribute' => 'funeral', 'header' => 'Ma chay', 'fallback' => 59, 'type' => 'numeric'],
            ['attribute' => 'funeral_notice', 'noticeAfter' => 'Ma chay', 'fallback' => 60, 'type' => 'string'],
            ['attribute' => 'birthday_money', 'header' => 'Tiền sinh nhật', 'fallback' => 61, 'type' => 'numeric'],
            ['attribute' => 'birthday_money_notice', 'noticeAfter' => 'Tiền sinh nhật', 'fallback' => 62, 'type' => 'string'],
            ['attribute' => 'previous_period_debt', 'header' => 'Tiền lương tháng trước bị thiếu', 'fallback' => 63, 'type' => 'numeric'],
            ['attribute' => 'previous_period_debt_notice', 'noticeAfter' => 'Tiền lương tháng trước bị thiếu', 'fallback' => 64, 'type' => 'string'],
            ['attribute' => 'total_income', 'header' => 'Tổng thu nhập', 'fallback' => 65, 'type' => 'numeric'],
            ['attribute' => 'insurance_detail', 'header' => 'Khấu trừ BHXH 10.5%', 'fallback' => 66, 'type' => 'numeric'],
            ['attribute' => 'insurance_detail_notice', 'noticeAfter' => 'Khấu trừ BHXH 10.5%', 'fallback' => 67, 'type' => 'string'],
            ['attribute' => 'advance_money', 'header' => 'Tạm ứng', 'fallback' => 68, 'type' => 'numeric'],
            ['attribute' => 'advance_money_notice', 'noticeAfter' => 'Tạm ứng', 'fallback' => 69, 'type' => 'string'],
            ['attribute' => 'number_of_violations', 'header' => 'Số lần vi phạm', 'fallback' => 70, 'type' => 'numeric'],
            ['attribute' => 'unicon_deduction', 'header' => 'Phí công đoàn 0.5%', 'fallback' => 71, 'type' => 'numeric'],
            ['attribute' => 'unicon_deduction_notice', 'noticeAfter' => 'Phí công đoàn 0.5%', 'fallback' => 72, 'type' => 'string'],
            ['attribute' => 'union_fee', 'header' => 'Phí công đoàn 0.5%', 'fallback' => 74, 'type' => 'numeric'],
            ['attribute' => 'union_fee_notice', 'noticeAfter' => 'Phí công đoàn 0.5%', 'fallback' => 75, 'type' => 'string'],
            ['attribute' => 'daysleave_allowed', 'header' => 'Số nghỉ có phép', 'fallback' => 73, 'type' => 'numeric'],
            ['attribute' => 'daysleave_allowed_notice', 'noticeAfter' => 'Số nghỉ có phép', 'fallback' => 77, 'type' => 'string'],
            ['attribute' => 'subtract_daysleave_allowed', 'header' => 'Trừ tiền nghỉ có phép', 'fallback' => 74, 'type' => 'numeric'],
            ['attribute' => 'subtract_daysleave_allowed_notice', 'noticeAfter' => 'Trừ tiền nghỉ có phép', 'fallback' => 75, 'type' => 'string'],
            ['attribute' => 'daysleave_notallowed', 'header' => 'Số nghỉ không có phép', 'fallback' => 76, 'type' => 'numeric'],
            ['attribute' => 'daysleave_notallowed_notice', 'noticeAfter' => 'Số nghỉ không có phép', 'fallback' => 79, 'type' => 'string'],
            ['attribute' => 'subtract_daysleave_notallowed', 'header' => 'Trừ tiền nghỉ không phép', 'fallback' => 77, 'type' => 'numeric'],
            ['attribute' => 'subtract_daysleave_notallowed_notice', 'noticeAfter' => 'Trừ tiền nghỉ không phép', 'fallback' => 78, 'type' => 'string'],
            ['attribute' => 'error_serious', 'header' => 'Số lỗi nặng', 'fallback' => 79, 'type' => 'numeric'],
            ['attribute' => 'error_serious_notice', 'noticeAfter' => 'Số lỗi nặng', 'fallback' => 81, 'type' => 'string'],
            ['attribute' => 'subtract_error_serious', 'header' => 'Trừ tiền số lỗi nặng', 'fallback' => 80, 'type' => 'numeric'],
            ['attribute' => 'subtract_error_serious_notice', 'noticeAfter' => 'Trừ tiền số lỗi nặng', 'fallback' => 81, 'type' => 'string'],
            ['attribute' => 'error_minor', 'header' => 'Số lỗi nhẹ', 'fallback' => 82, 'type' => 'numeric'],
            ['attribute' => 'error_minor_notice', 'noticeAfter' => 'Số lỗi nhẹ', 'fallback' => 83, 'type' => 'string'],
            ['attribute' => 'subtract_error_minor', 'header' => 'Trừ tiền số lỗi nhẹ', 'fallback' => 83, 'type' => 'numeric'],
            ['attribute' => 'subtract_error_minor_notice', 'noticeAfter' => 'Trừ tiền số lỗi nhẹ', 'fallback' => 84, 'type' => 'string'],
            ['attribute' => 'kpi_subtraction', 'header' => 'Bị trừ KPI tháng này', 'fallback' => 85, 'type' => 'numeric'],
            ['attribute' => 'kpi_subtraction_notice', 'noticeAfter' => 'Bị trừ KPI tháng này', 'fallback' => 85, 'type' => 'string'],
            ['attribute' => 'actually_received', 'header' => 'Thực lãnh', 'fallback' => 86, 'type' => 'numeric'],
            ['attribute' => 'forms_of_payment', 'header' => 'Hình thức thanh toán', 'fallback' => 87, 'type' => 'string'],
            ['attribute' => 'company_insurance_detail', 'header' => 'BHXH (21.5%) công ty đóng cho NLĐ', 'fallback' => 88, 'type' => 'numeric'],
        ];
    }

    private function valueForField(array $row, array $field)
    {
        $column = $this->columnForField($field);

        return $column !== null ? ($row[$column] ?? null) : null;
    }

    private function columnForField(array $field): ?int
    {
        if (! empty($this->headerColumns)) {
            if (isset($field['noticeAfter'])) {
                return $this->noticeColumnAfter($field['noticeAfter'], $field['occurrence'] ?? 1);
            }

            return $this->headerColumn($field['header'], $field['occurrence'] ?? 1);
        }

        return $field['fallback'] ?? null;
    }

    private function headerColumn(string $header, int $occurrence = 1): ?int
    {
        $columns = $this->headerColumns[$this->normalizeHeader($header)] ?? [];

        return $columns[$occurrence - 1] ?? null;
    }

    private function noticeColumnAfter(string $header, int $occurrence = 1): ?int
    {
        $column = $this->headerColumn($header, $occurrence);
        if ($column === null) {
            return null;
        }

        $noticeColumn = $column + 1;

        return ($this->headersByColumn[$noticeColumn] ?? null) === $this->normalizeHeader('Ghi chú')
            ? $noticeColumn
            : null;
    }

    private function fieldByAttribute(string $attribute): ?array
    {
        foreach ($this->detailFields() as $field) {
            if ($field['attribute'] === $attribute) {
                return $field;
            }
        }

        return null;
    }

    private function captureDetailHeaders($worksheet): void
    {
        $highestColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($worksheet->getHighestColumn());
        $this->headerColumns = [];
        $this->headersByColumn = [];

        for ($column = 1; $column <= $highestColumn; $column++) {
            $header = $this->normalizeHeader($worksheet->getCellByColumnAndRow($column, self::DETAIL_HEADER_ROW)->getValue());
            if ($header === '') {
                continue;
            }

            $rowIndex = $column - 1;
            $this->headersByColumn[$rowIndex] = $header;
            $this->headerColumns[$header][] = $rowIndex;
        }
    }

    private function normalizeHeader($value): string
    {
        $value = preg_replace('/[\s\x{00A0}]+/u', ' ', trim((string) $value));

        return mb_strtolower($value ?? '');
    }

    public function isEmptyWhen(array $row): bool
    {
        $employeeColumn = $this->headerColumn('Mã NV') ?? 1;
        if (empty($row[$employeeColumn])) {
            return true;
        }

        if (empty($this->employeeMap)) {
            $this->employeeMap = $this->getPreloadedEmployees();
        }

        return $this->getEmployeeFast((string) $row[$employeeColumn], $this->employeeMap) === null;
    }

    public function prepareForValidation(array $row, int $index): array
    {
        $field = $this->fieldByAttribute('actually_received');
        $column = $field ? $this->columnForField($field) : null;
        if ($column !== null) {
            $row[$column] = $this->normalizeNumericInput($row[$column] ?? null);
        }

        return $row;
    }

    // validate
    public function rules(): array
    {
        $rules = [];
        $listCode = $this->getValidEmployeeIds();
        $employeeColumn = $this->headerColumn('Mã NV') ?? 1;
        $rules[(string) $employeeColumn] = ['required', 'in:'.implode(',', $listCode)];

        foreach ($this->detailFields() as $field) {
            if (($field['type'] ?? 'numeric') !== 'numeric') {
                continue;
            }

            $column = $this->columnForField($field);
            if ($column !== null) {
                $rules[(string) $column] = ['nullable', 'numeric'];
            }
        }

        return $rules;
    }

    public function customValidationMessages()
    {
        $messages = [];
        $employeeColumn = $this->headerColumn('Mã NV') ?? 1;
        $messages[$employeeColumn.'.required'] = 'Mã nhân viên không được để trống!';
        $messages[$employeeColumn.'.in'] = 'Mã nhân viên không tồn tại!';

        foreach ($this->detailFields() as $field) {
            if (($field['type'] ?? 'numeric') !== 'numeric') {
                continue;
            }

            $column = $this->columnForField($field);
            if ($column !== null) {
                $messages[$column.'.numeric'] = 'Trường '.($field['header'] ?? $field['noticeAfter']).' không đúng định dạng!';
            }
        }

        return $messages;
    }

    /**
     * @param  Failure[]  $failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $key => $failure) {
            LogHelper::saveLog('Import-VVP-Chi tiết', $this->formatImportFailure($this->sheet(), $failure), $failure->row());
        }
    }

    /**
     * Register events for the import process
     */
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $this->captureDetailHeaders($event->sheet->getDelegate());
            },
            AfterImport::class => function () {
                $this->clearEmployeeCache();
                if ($this->salaryManagerId) {
                    $this->clearSalaryCache(SalaryOfficialVVP::class, $this->salaryManagerId);
                }
                $this->clearValidationCache();
            },
        ];
    }
}
