<?php

namespace App\Imports\VVP;

use App\Helpers\LogHelper;
use App\Models\Employee;
use App\Models\SalaryOfficialVVP;
use App\Models\SalaryOfficialVVPTimekeeping;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class SalaryOfficialVVPTimekepingImport implements
    ToArray,
    HasReferencesToOtherSheets,
    SkipsOnFailure,
    SkipsEmptyRows,
    WithValidation,
    WithStartRow
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
        return ' Bảng nhập công'; // Đặt tên sheet ở đây
    }

    /**
     * @param array $rows
     */
    public function array(array $rows)
    {
        // dd($rows);
        try {
            $dateStart = Carbon::parse($this->startDate);
            $dateEnd = Carbon::parse($this->endDate);
            foreach ($rows as $row) {
                if ($row[1] != null && $row[1] != '') {
                    $employee = Employee::where('code', $row[1])->first();
                    if ($employee != null  && $this->salaryManagerId != null) {
                        $salaryManager = SalaryOfficialVVP::where('salaries_manager_id', $this->salaryManagerId)->where('employee_id', $employee->id)->first();
                        if ($salaryManager) {
                            $countDate = $dateEnd->diffInDays($dateStart) + 1;
                            $limit = $countDate * 3 + 13;
                            $date = $this->startDate;

                            //chấm công chi tiết
                            for ($i = 13; $i < $limit; $i += 3) {
                                SalaryOfficialVVPTimekeeping::create([
                                    'salary_official_vvp_id' => $salaryManager->id,
                                    'timekeeping_date' => $date,                          //ngày chấm công
                                    'timekeeping_day' => $row[$i] ?? null,                //số giờ làm ngày
                                    'timekeeping_night' => $row[$i + 1] ?? null,          //số giờ làm đêm
                                    'timekeeping_overtime' => $row[$i + 2] ?? null,       //số giờ tăng ca
                                ]);
                                $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
                            }

                            //thông số chấm công tổng quát
                            $salaryManager->total_day_offical = $row[4] ?? null;                           //tổng ngày
                            $salaryManager->total_night_offical = $row[5] ?? null;                         //tổng đêm
                            $salaryManager->total_overtime_offical = $row[6] ?? null;                      //tổng tăng ca
                            $salaryManager->workday_count_trial = $row[7] ?? null;                         //số công ngày
                            $salaryManager->worknight_count_trial = $row[8] ?? null;                       //số công đêm
                            $salaryManager->overtime_day_count_trial = $row[9] ?? null;                    //số ngày tăng ca
                            $salaryManager->allowance_rice_day_timekeeping = $row[10] ?? null;             //Phụ cấp tiền cơm ngày
                            $salaryManager->allowance_rice_night_timekeeping = $row[11] ?? null;           //Phụ cấp tiền cơm đêm
                            $salaryManager->allowance_overtime_timekeeping = $row[12] ?? null;             //phụ cấp tăng ca

                            $salaryManager->holidays_count = $row[106] ?? null;                             //số ngày nghĩ lễ tết
                            $salaryManager->paid_holidays_count = $row[107] ?? null;                        //số ngày phép năm
                            $salaryManager->daysleave_allowed_timekeeping = $row[108] ?? null;              //số ngày nghỉ có phép
                            $salaryManager->daysleave_notallowed_timekeeping = $row[109] ?? null;           //số ngày nghỉ không phép
                            $salaryManager->save();
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            LogHelper::saveLog('Import-TimeKeeping-VVP', $e->getMessage(), $e->getLine());
            Log::error('errors time::: ' . $e->getMessage() . ' getLine' . $e->getLine());
        }
    }

    //validate
    public function rules(): array
    {
        $listCode = Employee::all()->pluck('code')->toArray();
        return [
            '1' => ['required', 'in:' . implode(',', $listCode)],
            '4' => ['nullable', 'numeric'],
            '5' => ['nullable', 'numeric'],
            '6' => ['nullable', 'numeric'],
            '7' => ['nullable', 'numeric'],
            '8' => ['nullable', 'numeric'],
            '9' => ['nullable', 'numeric'],
            '10' => ['nullable', 'numeric'],
            '11' => ['nullable', 'numeric'],
            '12' => ['nullable', 'numeric'],
            '103' => ['nullable', 'numeric'],
            '104' => ['nullable', 'numeric'],
            '105' => ['nullable', 'numeric'],
            '106' => ['nullable', 'numeric'],
            '107' => ['nullable', 'numeric'],
            '108' => ['nullable', 'numeric'],
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
            '103.numeric' => 'Số ngày nghĩ lễ tết không đúng định dạng!',
            '104.numeric' => 'Số ngày phép năm không đúng định dạng!',
            '105.numeric' => 'Số ngày hết việc không đúng định dạng!',
            '106.numeric' => 'Tăng cường ngày không đúng định dạng!',
            '107.numeric' => 'Tăng cường đêm không đúng định dạng!',
            '108.numeric' => 'Phép năm lũy kế thừa tháng này không đúng định dạng!',
        ];
    }

    //số dòng bỏ qua
    public function startRow(): int
    {
        return 8;
    }
    /**
     * @return array
     */


    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $key => $failure) {
            LogHelper::saveLog('Import-VVP-Chấm công', $failure->errors()[0], $failure->row());
        }
    }
}
