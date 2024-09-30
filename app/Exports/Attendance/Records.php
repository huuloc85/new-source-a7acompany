<?php

namespace App\Exports\Attendance;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\DefaultValueBinder;
use Maatwebsite\Excel\Events\AfterSheet;

class Records extends DefaultValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder, WithTitle, WithEvents
{
    protected $records;

    public function __construct($records)
    {
        $this->records = $records;
    }

    // Trả về tiêu đề sheet
    public function title(): string
    {
        return 'Bảng Chấm Công';
    }


    public function view(): View
    {
        return view('export.attendance.records', [
            'records' => $this->records,
        ]);
    }

    // Đăng ký sự kiện sau khi tạo sheet
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Cố định cột A và hàng 2 (freeze)
                $event->sheet->freezePane('C3');

                // Set zoom cho sheet
                $event->sheet->getSheetView()->setZoomScale(70);
            },
        ];
    }
}
