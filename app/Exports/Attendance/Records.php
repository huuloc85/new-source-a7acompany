<?php

namespace App\Exports\Attendance;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\DefaultValueBinder;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;

class Records extends DefaultValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder, WithTitle, WithEvents
{
    protected $records;
    protected $title;
    protected $startDate;
    protected $endDate;
    protected $currentMonth;

    public function __construct($records, $title, $startDate, $endDate, $currentMonth)
    {
        $this->records = $records;
        $this->title = $title;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->currentMonth = $currentMonth;
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
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->freezePane('C3');
                $event->sheet->getSheetView()->setZoomScale(70);
            },
        ];
    }
}
