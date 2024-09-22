<?php

namespace App\Imports\parttime;

use App\Helpers\LogHelper;
use App\Models\Employee;
use App\Models\SalaryParttime;
use App\Models\SalaryParttimeTimekeeping;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class SalaryParttimeTimekepingImport implements 
ToArray, 
HasReferencesToOtherSheets, 
WithHeadingRow,
WithValidation, 
SkipsOnFailure, 
SkipsEmptyRows
{
    public $salaryManagerId;
    public $startDate;
    public $endDate;
    public function __construct($salaryManagerId, $startDate, $endDate)
    {
        $this->salaryManagerId = $salaryManagerId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function sheet(): string
    {
        return 'LƯƠNG NGÀY LÃNH TUẦN'; // Tên sheet
    }

    /**
    * @param array $rows
    */
    public function array(array $rows)
    {
        try {
            $dateStart = Carbon::parse($this->startDate);
            $dateEnd = Carbon::parse($this->endDate);
            foreach ($rows as $row) {
                if ($row[1] != null && $row[1] != '') {
                    $employee = Employee::where('code', $row[1])->first();
                    if ($employee != null && $this->salaryManagerId != null) {
                        $salaryManager = SalaryParttime::where('salaries_manager_id', $this->salaryManagerId)->where('employee_id', $employee->id)->first();
                        if ($salaryManager) {
                            $countDate = $dateEnd->diffInDays($dateStart) + 1;
                            $limit = $countDate * 3 + 10;
                            $date = $this->startDate;
                            
                            //chấm công chi tiết
                            for ($i = 11; $i < $limit; $i += 3) {
                                SalaryParttimeTimekeeping::create([
                                    'salary_parttime_id' => $salaryManager->id,
                                    'timekeeping_date' => $date,                //ngày chấm công
                                    'timekeeping_day' => $row[$i] ?? null,              //số giờ làm ngày
                                    'timekeeping_night' => $row[$i + 1] ?? null,        //số giờ làm đêm
                                    'timekeeping_overtime' => $row[$i + 2] ?? null,     //số giờ tăng ca
                                ]);
                                $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
                            }
            
                            //thông số chấm công tổng quát
                            $salaryManager->total_day = $row[4] ?? null;                     //tổng ngày
                            $salaryManager->total_night = $row[5] ?? null;                   //tổng đêm
                            $salaryManager->total_overtime = $row[6] ?? null;                //tổng tăng ca
                            $salaryManager->workday_money = $row[7] ?? null;                 //số tiền ngày
                            $salaryManager->worknight_money = $row[8] ?? null;               //số công đêm
                            $salaryManager->allowance_outwork = $row[9] ?? null;             //phụ cấp hết việc
                            $salaryManager->salary_total_2 = $row[10] ?? null;               //tổng lương 2
                
                            $salaryManager->holidays_count = $row[104] ?? null;              //số ngày nghĩ lễ tết
                            $salaryManager->paid_holidays_count = $row[105] ?? null;         //số ngày phép năm
                            $salaryManager->outwork_day_count = $row[106] ?? null;           //số ngày hết việc
                            $salaryManager->increase_day = $row[107] ?? null;                //tăng cường ngày
                            $salaryManager->increase_night = $row[108] ?? null;              //tăng cường đêm
                            $salaryManager->annual_leave = $row[109] ?? null;                //phép năm lũy kế thừa tháng này
                            $salaryManager->save();
                        }
                    }
                }
            }
        }catch (\Exception $e) {
            LogHelper::saveLog('Import-Timekeeping-Parttime', $e->getMessage(), $e->getLine());
            Log::error('errors time-parttime::: ' . $e->getMessage() . ' getLine' . $e->getLine());
        }
    }

    public function headingRow(): int
    {
        //7
        return 8;
    }

    //validate
    public function rules(): array
    {
        $listCode = Employee::all()->pluck('code')->toArray();
        return [
            '1' => ['required', 'in:'.implode(',', $listCode)],
            '4' => ['nullable','numeric'],
            '5' => ['nullable','numeric'],
            '6' => ['nullable','numeric'],
            '7' => ['nullable','numeric'],
            '8' => ['nullable','numeric'],
            '9' => ['nullable','numeric'],
            '10' => ['nullable','numeric'],
            '104' => ['nullable','numeric'],
            '105' => ['nullable','numeric'],
            '106' => ['nullable','numeric'],
            '107' => ['nullable','numeric'],
            '108' => ['nullable','numeric'],
            '109' => ['nullable','numeric'],
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
            '9.numeric' => 'Phụ cấu hết việc không đúng định dạng!',
            '9.numeric' => 'Tổng lương không đúng định dạng!',
            '103.numeric' => 'Số ngày nghĩ lễ tết không đúng định dạng!',
            '104.numeric' => 'Số ngày phép năm không đúng định dạng!',
            '105.numeric' => 'Số ngày hết việc không đúng định dạng!',
            '106.numeric' => 'Tăng cường ngày không đúng định dạng!',
            '107.numeric' => 'Tăng cường đêm không đúng định dạng!',
            '108.numeric' => 'Phép năm lũy kế thừa tháng này không đúng định dạng!',
        ];
    }

    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $key => $failure) {
            LogHelper::saveLog('Import-Parttime-Chấm công', $failure->errors()[0], $failure->row());
        }
    }
}
