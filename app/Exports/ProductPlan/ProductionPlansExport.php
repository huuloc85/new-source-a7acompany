<?php

namespace App\Exports\ProductPlan;

use App\Models\ProductionPlan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\DefaultValueBinder;
use Maatwebsite\Excel\Events\AfterSheet;

class ProductionPlansExport extends DefaultValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder, WithTitle, WithEvents
{
    protected $month;

    public function __construct($month)
    {
        $this->month = $month;
    }

    public function view(): View
    {
        $plans = ProductionPlan::where('month', $this->month)->get();

        // return view('exports.production_plans', compact('plans'));
        return view('export/productPlan/index', compact('plans'));
    }

    public function title(): string
    {
        return 'KẾ HOẠCH SẢN XUẤT';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Đóng băng hai cột đầu tiên (cột A và B)
                $event->sheet->freezePane('C3');
                $event->sheet->getSheetView()->setZoomScale(160);
            },
        ];
    }
}
