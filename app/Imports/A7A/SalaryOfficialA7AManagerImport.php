<?php

namespace App\Imports\A7A;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SalaryOfficialA7AManagerImport implements WithMultipleSheets
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
            'Danh muc' => new SalaryOfficialA7ACategoryImport($this->salaryManagerId),
            'Bang Thanh Toan Luong' => new SalaryOfficialA7APayrollImport($this->salaryManagerId),
            'Bảng tính toán' => new SalaryOfficialA7ADetailImport($this->salaryManagerId),
            ' Bảng nhập công' => new SalaryOfficialA7ATimekepingImport($this->salaryManagerId, $this->startDate, $this->endDate),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        info("Sheet {$sheetName} was skipped");
    }
}
