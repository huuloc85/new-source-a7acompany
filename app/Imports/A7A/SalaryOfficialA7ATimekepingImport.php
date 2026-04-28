<?php

namespace App\Imports\A7A;

use App\Helpers\LogHelper;
use App\Imports\Traits\OptimizesSalaryImport;
use App\Models\SalaryOfficialA7A;
use App\Models\SalaryOfficialA7ATimekeeping;
use Carbon\Carbon;
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
use Maatwebsite\Excel\Validators\Failure;

class SalaryOfficialA7ATimekepingImport implements HasReferencesToOtherSheets, SkipsEmptyRows, SkipsOnFailure, ToArray, WithChunkReading, WithEvents, WithStartRow, WithValidation, WithCalculatedFormulas
{
    use OptimizesSalaryImport;

    public $salaryManagerId;

    public $startDate;

    public $endDate;

    private $employeeMap = [];
    private $salaryMap = [];

    public function __construct($salaryManagerId, $startDate, $endDate)
    {
        $this->salaryManagerId = $salaryManagerId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function chunkSize(): int
    {
        return 300; // Smaller chunk for timekeeping due to nested inserts
    }

    public function sheet(): string
    {
        return ' Bảng chấm công'; // Đặt tên sheet ở đây
    }

    public function array(array $rows)
    {
        try {
            $dateStart = Carbon::parse($this->startDate)->startOfDay();
            $countDate = Carbon::parse($this->endDate)->startOfDay()->diffInDays($dateStart) + 1;
            $limit = $countDate * 3 + 13;
            $dateList = [];
            for ($offset = 0; $offset < $countDate; $offset++) {
                $dateList[] = $dateStart->copy()->addDays($offset)->format('Y-m-d');
            }

            // Preload data once before processing
            if (empty($this->employeeMap)) {
                $this->employeeMap = $this->getPreloadedEmployees();
            }
            if (empty($this->salaryMap) && $this->salaryManagerId) {
                $this->salaryMap = $this->getPreloadedSalaryRecords(SalaryOfficialA7A::class, $this->salaryManagerId);
            }

            $insertTimekeepings = [];
            $updates = [];
            $now = now();
            $updateColumns = [
                'total_day_offical',
                'total_night_offical',
                'total_overtime_offical',
                'workday_count_trial',
                'worknight_count_trial',
                'overtime_day_count_trial',
                'allowance_rice_day_timekeeping',
                'allowance_rice_night_timekeeping',
                'allowance_overtime_timekeeping',
                'holidays_count',
                'paid_holidays_count',
                'daysleave_allowed_timekeeping',
                'daysleave_notallowed_timekeeping',
                'updated_at',
            ];

            foreach ($rows as $row) {
                if ($row[1] != null && $row[1] != '') {
                    $employee = $this->getEmployeeFast($row[1], $this->employeeMap);
                    if ($employee != null && $this->salaryManagerId != null) {
                        $salaryManager = $this->getSalaryRecordFast($this->salaryMap, $employee->id);
                        if ($salaryManager) {
                            // chấm công chi tiết
                            for ($dayIndex = 0, $i = 13; $i < $limit; $i += 3, $dayIndex++) {
                                $insertTimekeepings[] = [
                                    'salary_official_a7a_id' => $salaryManager->id,
                                    'timekeeping_date' => $dateList[$dayIndex],                          // ngày chấm công
                                    'timekeeping_day' => (is_numeric($row[$i] ?? null) ? (float)$row[$i] : null),                // số giờ làm ngày
                                    'timekeeping_night' => (is_numeric($row[$i + 1] ?? null) ? (float)$row[$i + 1] : null),          // số giờ làm đêm
                                    'timekeeping_overtime' => (is_numeric($row[$i + 2] ?? null) ? (float)$row[$i + 2] : null),       // số giờ tăng ca
                                    'created_at' => $now,
                                    'updated_at' => $now,
                                ];
                            }

                            $updates[] = [
                                'id' => $salaryManager->id,
                                'salaries_manager_id' => $salaryManager->salaries_manager_id,
                                'employee_id' => $salaryManager->employee_id,
                                'total_day_offical' => $this->numericValue($row[4] ?? null),
                                'total_night_offical' => $this->numericValue($row[5] ?? null),
                                'total_overtime_offical' => $this->numericValue($row[6] ?? null),
                                'workday_count_trial' => $this->numericValue($row[7] ?? null),
                                'worknight_count_trial' => $this->numericValue($row[8] ?? null),
                                'overtime_day_count_trial' => $this->numericValue($row[9] ?? null),
                                'allowance_rice_day_timekeeping' => $this->numericValue($row[10] ?? null),
                                'allowance_rice_night_timekeeping' => $this->numericValue($row[11] ?? null),
                                'allowance_overtime_timekeeping' => $this->numericValue($row[12] ?? null),
                                'holidays_count' => $this->numericValue($row[106] ?? null),
                                'paid_holidays_count' => $this->numericValue($row[107] ?? null),
                                'daysleave_allowed_timekeeping' => $this->numericValue($row[108] ?? null),
                                'daysleave_notallowed_timekeeping' => $this->numericValue($row[109] ?? null),
                                'created_at' => $salaryManager->created_at,
                                'updated_at' => $now,
                            ];
                        }
                    }
                }
            }

            foreach (array_chunk($insertTimekeepings, 1000) as $chunk) {
                SalaryOfficialA7ATimekeeping::insert($chunk);
            }
            $this->batchUpsertById(SalaryOfficialA7A::class, $updates, $updateColumns);
        } catch (\Exception $e) {
            LogHelper::saveLog('Import-Timekeeping-A7A', $e->getMessage(), $e->getLine());
            Log::error('errors time-a7a::: '.$e->getMessage().' getLine'.$e->getLine());
            throw $e;
        }
    }

    // validate
    public function rules(): array
    {
        $listCode = $this->getValidEmployeeIds();

        return [
            '1' => ['required', 'in:'.implode(',', $listCode)],
            '4' => ['nullable', 'numeric'],
            '5' => ['nullable', 'numeric'],
            '6' => ['nullable', 'numeric'],
            '7' => ['nullable', 'numeric'],
            '8' => ['nullable', 'numeric'],
            '9' => ['nullable', 'numeric'],
            '10' => ['nullable', 'numeric'],
            '11' => ['nullable', 'numeric'],
            '12' => ['nullable', 'numeric'],
            '106' => ['nullable', 'numeric'],
            '107' => ['nullable', 'numeric'],
            '108' => ['nullable', 'numeric'],
            '109' => ['nullable', 'numeric'],
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            '1.required' => 'Mã nhân viên không được để trống!',
            '1.in' => 'Mã nhân viên không tồn tại!',
            '4.numeric' => 'Tổng ngày không đúng định dạng!',
            '5.numeric' => 'Tổng đêm không đúng định dạng!',
            '6.numeric' => 'Tổng tăng ca không đúng định dạng!',
            '7.numeric' => 'Số công ngày không đúng định dạng!',
            '8.numeric' => 'Số công đêm không đúng định dạng!',
            '9.numeric' => 'Số ngày tăng ca không đúng định dạng!',
            '10.numeric' => 'Phụ cấp tiền cơm ngày không đúng định dạng!',
            '11.numeric' => 'Phụ cấp tiền cơm đêm không đúng định dạng!',
            '12.numeric' => 'Phụ cấp tăng ca không đúng định dạng!',
            '106.numeric' => 'Số ngày nghĩ lễ tết không đúng định dạng!',
            '107.numeric' => 'Số ngày phép năm không đúng định dạng!',
            '108.numeric' => 'Số ngày nghỉ có phép không đúng định dạng!',
            '109.numeric' => 'Số ngày nghỉ không phép không đúng định dạng!',
        ];
    }

    /**
     * @param  Failure[]  $failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $key => $failure) {
            LogHelper::saveLog('Import-A7A-Chấm công', $failure->errors()[0], $failure->row());
        }
    }

    /**
     * Register events for the import process
     */
    public function registerEvents(): array
    {
        return [
            AfterImport::class => function() {
                $this->clearEmployeeCache();
                if ($this->salaryManagerId) {
                    $this->clearSalaryCache(SalaryOfficialA7A::class, $this->salaryManagerId);
                }
                $this->clearValidationCache();
            },
        ];
    }

    public function startRow(): int
    {
        // 7
        return 8;
    }
}
