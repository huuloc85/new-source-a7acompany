<?php

namespace App\Imports\VVP;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SalaryOfficialVVPManagerImport implements WithMultipleSheets
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
            'Danh muc' => new SalaryOfficialVVPCategoryImport($this->salaryManagerId),
            'Bang Thanh Toan Luong' => new SalaryOfficialVVPPayrollImport($this->salaryManagerId),
            'Bảng tính toán' => new SalaryOfficialVVPDetailImport($this->salaryManagerId),
            ' Bảng nhập công' => new SalaryOfficialVVPTimekepingImport($this->salaryManagerId, $this->startDate, $this->endDate),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        info("Sheet {$sheetName} was skipped");
    }
}
