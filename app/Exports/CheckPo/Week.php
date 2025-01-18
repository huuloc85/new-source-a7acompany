<?php

namespace App\Exports\Checkpo;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class Week extends DefaultValueBinder implements FromView, ShouldAutoSize, WithEvents, WithTitle
{
    protected $index;

    protected $weekDates;

    protected $sheetName;

    protected $month;

    public function __construct($i, $weekArray, $sheetName, $month)
    {
        $this->index = $i;
        $this->weekDates = $weekArray;
        $this->sheetName = $sheetName;
        $this->month = $month;
    }

    public function view(): View
    {
        $weekDates = $this->weekDates;
        $index = $this->index;
        $products = Product::all();

        return view('export/checkpo/week', compact('products', 'weekDates', 'index'));
    }

    public function title(): string
    {
        return $this->sheetName.' - Tháng '.$this->month;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Freeze the first row and first two columns
                $event->sheet->freezePane('C2');
                // Set zoom scale
                $event->sheet->getSheetView()->setZoomScale(160);
                // Wrap text for columns B and C
                $event->sheet->getStyle('B:C')->getAlignment()->setWrapText(true);
            },
        ];
    }
}
