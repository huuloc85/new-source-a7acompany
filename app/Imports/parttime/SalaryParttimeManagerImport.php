<?php

namespace App\Imports\parttime;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SalaryParttimeManagerImport implements WithMultipleSheets
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

    public function sheets(): array
    {
        return [
            'Bang Thanh Toan Luong' => new SalaryParttimePayrollImport($this->salaryManagerId),
            'LƯƠNG NGÀY LÃNH TUẦN' => new SalaryParttimeTimekepingImport($this->salaryManagerId, $this->startDate, $this->endDate),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        info("Sheet {$sheetName} was skipped");
    }
}
