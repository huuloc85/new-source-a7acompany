<?php

namespace App\Exports\Attendance;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiSheetExport implements WithMultipleSheets
{
    protected $sheetsData;
    protected $startDate;
    protected $endDate;
    protected $currentMonth;
    protected $listDate;

    public function __construct(array $sheetsData, $startDate, $endDate, $currentMonth, $listDate)
    {
        $this->sheetsData = $sheetsData;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->currentMonth = $currentMonth;
        $this->listDate = $listDate;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->sheetsData as $title => $records) {
            $sheets[] = new Records($records, $title, $this->startDate, $this->endDate, $this->currentMonth, $this->listDate);
        }

        return $sheets;
    }
}
