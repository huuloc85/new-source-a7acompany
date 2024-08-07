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
use App\Models\Product;
use Carbon\Carbon;

class ProductionPlansExport extends DefaultValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder, WithTitle, WithEvents
{
    protected $month;
    protected $time;
    protected $daysInMonth;
    protected $daysInMonthYMD;
    const ACTUAL_PRODUCT = 1;

    public function __construct($month, $time, $daysInMonth, $daysInMonthYMD)
    {
        $this->month = $month;
        $this->time = $time;
        $this->daysInMonth = $daysInMonth;
        $this->daysInMonthYMD = $daysInMonthYMD;
    }

    public function view(): View
    {
        $plans = ProductionPlan::where('month', $this->month)->get();
        $data = [];
        $month = Carbon::now()->format('m-Y');
        $products = Product::all();
        foreach ($products as $product) {
            $totalQuantityMonth = Product::join('totalmonthquantities', 'products.id', '=', 'totalmonthquantities.product_id')
                ->where('totalmonthquantities.month', $this->time->format('Y-m'))
                ->where('totalmonthquantities.status', self::ACTUAL_PRODUCT)
                ->where('products.id', $product->id)
                ->value('totalmonthquantities.totalQuan') ?? 0;

            $data[] = [
                'name' => $product->name,
                'id' => $product->id,
                'totalQuantityMonth' => $totalQuantityMonth
            ];
        }

        $daysArray = $this->generateDaysInMonth($this->month);
        $formattedDaysArray = $this->convertDateFormat($daysArray);

        return view('export.productPlan.index', compact('plans', 'data', 'formattedDaysArray', 'month'));
    }

    public function title(): string
    {
        return 'KẾ HOẠCH SẢN XUẤT';
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

    protected function generateDaysInMonth($monthYear)
    {
        $startDate = Carbon::createFromFormat('m-Y', $monthYear)->startOfMonth();
        $daysInMonth = $startDate->daysInMonth;
        $daysArray = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $daysArray[] = $startDate->copy()->addDays($day - 1)->format('d-m-Y');
        }

        return $daysArray;
    }

    protected function convertDateFormat($dateArray)
    {
        $formattedDateArray = [];

        foreach ($dateArray as $dateString) {
            $parts = explode('-', $dateString);
            $formattedDate = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
            $formattedDateArray[] = $formattedDate;
        }

        return $formattedDateArray;
    }
}
