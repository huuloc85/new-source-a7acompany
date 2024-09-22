<?php

namespace App\Exports\Product;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;



class ProductError extends  DefaultValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder, WithTitle, WithEvents
{
    protected $time;
    protected $delimiter;
    protected $daysInMonth;
    protected $daysInMonthYMD;
    const ERROR_PRODUCT  = 6;

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
        $totalQuantityPerDay = [];
        $products = Product::all();

        foreach ($products as $product) {
            $totalQuantityMonth = Product::join('totalmonthquantities', 'products.id', '=', 'totalmonthquantities.product_id')
                ->where('totalmonthquantities.month', $this->time)
                ->where('totalmonthquantities.status', self::ERROR_PRODUCT)
                ->where('products.id', $product->id)
                ->value('totalmonthquantities.totalQuan') ?? 0;

            foreach ($this->daysInMonthYMD as $dayInMonthYMD) {
                $totalQuantityDate = Product::join('totaldailyquantities', 'products.id', '=', 'totaldailyquantities.product_id')
                    ->where('totaldailyquantities.date', $dayInMonthYMD)
                    ->where('totaldailyquantities.status', self::ERROR_PRODUCT)
                    ->where('products.id', $product->id)
                    ->value('totaldailyquantities.totalQuan') ?? '';

                $totalQuantityPerDay[$dayInMonthYMD] = $totalQuantityDate;
            }

            $data[] = [
                'name' => $product->name,
                'totalQuantityMonth' => $totalQuantityMonth,
                'totalQuantityPerDay' => $totalQuantityPerDay
            ];
        }

        $title = 'Danh sách hàng hóa';
        return view('export/product/error', compact('data', 'title', 'daysInMonth'));
    }

    public function title(): string
    {
        return 'HÀNG LỖI';
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
