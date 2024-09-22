<?php

namespace App\Imports\Celender;

use App\Helpers\LogHelper;
use App\Models\CelenderDetailWC;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CelenderWCImport implements
    ToArray,
    HasReferencesToOtherSheets,
    WithValidation,
    SkipsOnFailure,
    SkipsEmptyRows,
    WithStartRow
{
    public $celenderId;
    public function __construct($celenderId)
    {
        $this->celenderId = $celenderId;
    }

    public function sheet(): string
    {
        return 'ĐỔ RÁC WC'; // Đặt tên sheet ở đây
    }

    /**
     * @param array $rows
     */
    public function array(array $rows)
    {
        try {
            foreach ($rows as $row) {
                if ($row[1] != null && $row[1] != '') {
                    $employee = Employee::where('code', $row[1])->first();
                    if ($employee != null) {
                        $checkCelenderDetailWC = CelenderDetailWC::where('celender_id', $this->celenderId)->where('employee_id', $employee->id)->first();
                        if ($checkCelenderDetailWC == null) {
                            CelenderDetailWC::create([
                                'celender_id' => $this->celenderId,
                                'employee_id' => $employee->id,
                                'day1' => $row['3'] ?? null,
                                'day2' => $row['4'] ?? null,
                                'day3' => $row['5'] ?? null,
                                'day4' => $row['6'] ?? null,
                                'day5' => $row['7'] ?? null,
                            ]);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            LogHelper::saveLog('Import-Celender-WC', $e->getMessage(), $e->getLine());
            Log::error('errors cate::: ' . $e->getMessage() . ' getLine' . $e->getLine());
        }
    }

    //validate
    public function rules(): array
    {
        $listCode = Employee::all()->pluck('code')->toArray();
        return [
            '1' => ['required', 'in:' . implode(',', $listCode)],
            '3' => ['nullable', 'string'],
            '4' => ['nullable', 'string'],
            '5' => ['nullable', 'string'],
            '6' => ['nullable', 'string'],
            '7' => ['nullable', 'string'],
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
            '3.string' => 'Kí hiệu phải là định dạng chuỗi',
            '4.string' => 'Kí hiệu phải là định dạng chuỗi',
            '5.string' => 'Kí hiệu phải là định dạng chuỗi',
            '6.string' => 'Kí hiệu phải là định dạng chuỗi',
            '7.string' => 'Kí hiệu phải là định dạng chuỗi',
        ];
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 7;
    }

    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $key => $failure) {
            LogHelper::saveLog('Import-Celender-WC', $failure->errors()[0], $failure->row());
        }
    }
}
