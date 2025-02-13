<?php

namespace App\Exports\Attendance;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\DefaultValueBinder;
use Maatwebsite\Excel\Events\AfterSheet;

class Records extends DefaultValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder, WithEvents, WithTitle
{
    protected $records;

    protected $title;

    protected $startDate;

    protected $endDate;

    protected $currentMonth;

    protected $listDate;

    public function __construct($records, $title, $startDate, $endDate, $currentMonth, $listDate)
    {
        $this->records = $records;
        $this->title = $title;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->currentMonth = $currentMonth;
        $this->listDate = $listDate;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function view(): View
    {
        return view('export.attendance.records', [
            'records' => $this->records,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'currentMonth' => $this->currentMonth,
            'listDate' => $this->listDate,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->freezePane('K4');
                $event->sheet->getSheetView()->setZoomScale(60);
            },
        ];
    }
}
