<?php

namespace App\Exports\Product;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ProductCheck200Export extends DefaultValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder, WithTitle, WithEvents
{
    protected $time;
    protected $delimiter;
    protected $daysInMonth;
    protected $daysInMonthYMD;
    const IMPORT_200 = 2;
    const CHECKED_INVENTORY_200 = 5;


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
            $totalQuantityMonth = $this->getQuantity($this->time, self::IMPORT_200, $product->id);
            $totalQuantityInventory = $this->getQuantity($this->time, self::CHECKED_INVENTORY_200, $product->id);

            foreach ($this->daysInMonthYMD as $dayInMonthYMD) {
                $totalQuantityDate = Product::join('totaldailyquantities', 'products.id', '=', 'totaldailyquantities.product_id')
                    ->where('totaldailyquantities.date', $dayInMonthYMD)
                    ->where('totaldailyquantities.status', self::IMPORT_200)
                    ->where('products.id', $product->id)
                    ->value('totaldailyquantities.totalQuan') ?? '';

                $totalQuantityPerDay[$dayInMonthYMD] = $totalQuantityDate;
            }

            $data[] = [
                'name' => $product->name,
                'totalQuantityInventory' => $totalQuantityInventory,
                'totalQuantityMonth' => $totalQuantityMonth,
                'totalQuantityPerDay' => $totalQuantityPerDay
            ];
        }
        $title = 'Danh sách hàng hóa';
        return view('export/product/check-200-export', compact('data', 'title', 'daysInMonth'));
    }

    public function title(): string
    {
        return 'HÀNG KIỂM 200%';
    }

    public function getQuantity($time, $status, $productId)
    {
        return  Product::join('totalmonthquantities', 'products.id', '=', 'totalmonthquantities.product_id')
            ->where('totalmonthquantities.month', $time)
            ->where('totalmonthquantities.status', $status)
            ->where('products.id', $productId)
            ->value('totalmonthquantities.totalQuan') ?? 0;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Đóng băng hai cột đầu tiên (cột A và B)
                // Đóng băng hàng đầu tiên
                $event->sheet->freezePane('D3');
                $event->sheet->getSheetView()->setZoomScale(160);
                $event->sheet->getStyle('B:C')->getAlignment()->setWrapText(true);
            },
        ];
    }
}
