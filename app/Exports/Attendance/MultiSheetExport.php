<?php

namespace App\Exports\Attendance;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiSheetExport implements WithMultipleSheets
{
    protected $sheetsData;
    protected $startDate;
    protected $endDate;
    protected $currentMonth;

    public function __construct(array $sheetsData, $startDate, $endDate, $currentMonth)
    {
        $this->sheetsData = $sheetsData;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->currentMonth = $currentMonth;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->sheetsData as $title => $records) {
            $sheets[] = new Records($records, $title, $this->startDate, $this->endDate, $this->currentMonth);
        }

        return $sheets;
    }
}
