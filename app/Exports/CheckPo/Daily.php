<?php

namespace App\Exports\Checkpo;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class Daily extends DefaultValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder, WithTitle, WithEvents
{
    protected $time;
    protected $delimiter;
    protected $daysInMonth;
    protected $daysInMonthYMD;
    const ACTUAL_PRODUCT  = 1;

    public function __construct($time, $daysInMonth, $daysInMonthYMD)
    {
        $this->time = $time;
        $this->daysInMonth = $daysInMonth;
        $this->daysInMonthYMD = $daysInMonthYMD;
    }

    public function view(): View
    {
        $daysInMonth = $this->daysInMonth;
        $data = [];
        $products = Product::all();
        foreach ($products as $product) {
            $totalQuantityMonth = Product::join('totalmonthquantities', 'products.id', '=', 'totalmonthquantities.product_id')
                ->where('totalmonthquantities.month', $this->time)
                ->where('totalmonthquantities.status', self::ACTUAL_PRODUCT)
                ->where('products.id', $product->id)
                ->value('totalmonthquantities.totalQuan') ?? 0;

            $data[] = [
                'name' => $product->name,
                'id' => $product->id,
                'totalQuantityMonth' => $totalQuantityMonth
            ];
        }


        return view('export/checkpo/daily', compact('data', 'products', 'daysInMonth'));
    }

    public function title(): string
    {
        return 'HẰNG NGÀY';
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
