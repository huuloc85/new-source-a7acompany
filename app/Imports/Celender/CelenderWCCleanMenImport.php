<?php

namespace App\Imports\Celender;

use App\Helpers\LogHelper;
use App\Models\CelenderDetailWCCleanMen;
use App\Models\Employee;

use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CelenderWCCleanMenImport implements
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
        return 'Trực WC Nam'; // Đặt tên sheet ở đây
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
                        $checkCelenderDetailWCCleanMen = CelenderDetailWCCleanMen::where('celender_id', $this->celenderId)->where('employee_id', $employee->id)->first();
                        if ($checkCelenderDetailWCCleanMen == null) {
                            CelenderDetailWCCleanMen::create([
                                'celender_id' => $this->celenderId,
                                'employee_id' => $employee->id,
                                'day1' => $row['3'] ?? null,
                                'day2' => $row['4'] ?? null,
                                'day3' => $row['5'] ?? null,
                                'day4' => $row['6'] ?? null,
                                'day5' => $row['7'] ?? null,
                                'day6' => $row['8'] ?? null,
                                'day7' => $row['9'] ?? null,
                                'day8' => $row['10'] ?? null,
                                'day9' => $row['11'] ?? null,
                                'day10' => $row['12'] ?? null,
                                'day11' => $row['13'] ?? null,
                                'day12' => $row['14'] ?? null,
                                'day13' => $row['15'] ?? null,
                                'day14' => $row['16'] ?? null,
                                'day15' => $row['17'] ?? null,
                                'day16' => $row['18'] ?? null,
                                'day17' => $row['19'] ?? null,
                                'day18' => $row['20'] ?? null,
                                'day19' => $row['21'] ?? null,
                                'day20' => $row['22'] ?? null,
                                'day21' => $row['23'] ?? null,
                                'day22' => $row['24'] ?? null,
                                'day23' => $row['25'] ?? null,
                                'day24' => $row['26'] ?? null,
                                'day25' => $row['27'] ?? null,
                                'day26' => $row['28'] ?? null,
                                'day27' => $row['29'] ?? null,
                                'day28' => $row['30'] ?? null,
                                'day29' => $row['31'] ?? null,
                                'day30' => $row['32'] ?? null,
                                'day31' => $row['33'] ?? null,
                            ]);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            LogHelper::saveLog('Import-Celender-WC-Clean', $e->getMessage(), $e->getLine());
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
            '8' => ['nullable', 'string'],
            '9' => ['nullable', 'string'],
            '10' => ['nullable', 'string'],
            '11' => ['nullable', 'string'],
            '12' => ['nullable', 'string'],
            '13' => ['nullable', 'string'],
            '14' => ['nullable', 'string'],
            '15' => ['nullable', 'string'],
            '16' => ['nullable', 'string'],
            '17' => ['nullable', 'string'],
            '18' => ['nullable', 'string'],
            '19' => ['nullable', 'string'],
            '20' => ['nullable', 'string'],
            '21' => ['nullable', 'string'],
            '22' => ['nullable', 'string'],
            '23' => ['nullable', 'string'],
            '24' => ['nullable', 'string'],
            '25' => ['nullable', 'string'],
            '26' => ['nullable', 'string'],
            '27' => ['nullable', 'string'],
            '28' => ['nullable', 'string'],
            '29' => ['nullable', 'string'],
            '30' => ['nullable', 'string'],
            '31' => ['nullable', 'string'],
            '32' => ['nullable', 'string'],
            '33' => ['nullable', 'string'],
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
            '8.string' => 'Kí hiệu phải là định dạng chuỗi',
            '9.string' => 'Kí hiệu phải là định dạng chuỗi',
            '10.string' => 'Kí hiệu phải là định dạng chuỗi',
            '11.string' => 'Kí hiệu phải là định dạng chuỗi',
            '12.string' => 'Kí hiệu phải là định dạng chuỗi',
            '13.string' => 'Kí hiệu phải là định dạng chuỗi',
            '14.string' => 'Kí hiệu phải là định dạng chuỗi',
            '15.string' => 'Kí hiệu phải là định dạng chuỗi',
            '16.string' => 'Kí hiệu phải là định dạng chuỗi',
            '17.string' => 'Kí hiệu phải là định dạng chuỗi',
            '18.string' => 'Kí hiệu phải là định dạng chuỗi',
            '19.string' => 'Kí hiệu phải là định dạng chuỗi',
            '20.string' => 'Kí hiệu phải là định dạng chuỗi',
            '21.string' => 'Kí hiệu phải là định dạng chuỗi',
            '22.string' => 'Kí hiệu phải là định dạng chuỗi',
            '23.string' => 'Kí hiệu phải là định dạng chuỗi',
            '24.string' => 'Kí hiệu phải là định dạng chuỗi',
            '25.string' => 'Kí hiệu phải là định dạng chuỗi',
            '26.string' => 'Kí hiệu phải là định dạng chuỗi',
            '27.string' => 'Kí hiệu phải là định dạng chuỗi',
            '28.string' => 'Kí hiệu phải là định dạng chuỗi',
            '29.string' => 'Kí hiệu phải là định dạng chuỗi',
            '30.string' => 'Kí hiệu phải là định dạng chuỗi',
            '31.string' => 'Kí hiệu phải là định dạng chuỗi',
            '32.string' => 'Kí hiệu phải là định dạng chuỗi',
            '33.string' => 'Kí hiệu phải là định dạng chuỗi',
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
            LogHelper::saveLog('Import-Celender-WC-Clean-Men', $failure->errors()[0], $failure->row());
        }
    }
}
