<?php

namespace App\Exports\Product;

use App\Models\Product;
use App\Models\TotalMonthQuantity;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class FAPVExport extends DefaultValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder, WithTitle, WithEvents
{
    protected $time;
    protected $delimiter;
    const STOCK_QUANTITY200 = 5;
    const INVENTORY_PRODUCT  = 4;
    const EXPORT_200  = 3;
    const IMPORTED_QUANTITY  = 2;
    const ACTUAL_PRODUCT  = 1;
    const ERROR_PRODUCT  = 6;
    public function __construct($time)
    {
        $this->time = $time;
    }

    public function view(): View
    {
        $data = [];
        $productModel = new Product;
        $products = Product::all();
        $models = $productModel->models;
        $listMonthExport = TotalMonthQuantity::where('status', 3)->distinct()->pluck('month');


        $time = $this->time;
        foreach ($products as $product) {
            $stockQuantity200 = $this->getQuantity($this->time, self::STOCK_QUANTITY200, $product->id);
            $stockQuantity = $this->getQuantity($this->time, self::INVENTORY_PRODUCT, $product->id);
            $exportedQuantity = $this->getQuantity($this->time, self::EXPORT_200, $product->id);
            $importedQuantity = $this->getQuantity($this->time, self::IMPORTED_QUANTITY, $product->id);
            $realityQuantity = $this->getQuantity($this->time, self::ACTUAL_PRODUCT, $product->id);
            $quanError = $this->getQuantity($this->time, self::ERROR_PRODUCT, $product->id);
            // Truy vấn stockMOQ theo product_id, status và tháng hiện tại
            $stockMOQ = TotalMonthQuantity::where('status', 7)
                ->where('product_id', $product->id)
                ->where('month', $time)
                ->distinct()
                ->pluck('totalQuan');
            $firstStockMOQ = $stockMOQ->isNotEmpty() ? $stockMOQ->first() : 0;

            $totalQuantityExportPerMonth = [];

            foreach ($listMonthExport as $month) {
                $exportedQuantityByMonth = $this->getQuantity($month, self::EXPORT_200, $product->id);
                $totalQuantityExportPerMonth[$month] = $exportedQuantityByMonth;
            }
            $stockEndQuantity = $stockQuantity + $realityQuantity - $exportedQuantity - $quanError;
            $checked200 = $stockQuantity200 + $importedQuantity - $exportedQuantity;
            $unchecked200 = $stockEndQuantity - $checked200;

            $daysInventory = 0;
            if ($firstStockMOQ != 0) {
                $daysInventory = $stockEndQuantity / ($firstStockMOQ / 24);
            }

            $quantityCaTon = $firstStockMOQ / $product->quanEntityBin;
            $planTime = (((($firstStockMOQ / $product->CAV) * $product->cycle) / 3600 / 24) * 100) / 90;
            $realTime = (((($exportedQuantity / $product->CAV) * $product->cycle) / 3600 / 24) * 100) / 90;

            $data[] = [
                'code' => $product->code,
                'name' => $product->name,
                'quantity' => $product->quantity,
                'quantityCaTon' => $quantityCaTon,
                'moldSize' => $product->moldSize,
                'CAV' => $product->CAV,
                'cycle' => $product->cycle,
                'planTime' => $planTime,
                'realTime' => $realTime,
                'FAPV' => $product->FAPV,
                'FASV' => $product->FASV,
                'FAVV' => $product->FAVV,
                'binCode' => $product->binCode,
                'quanEntityBin' => $product->quanEntityBin,
                'stockQuantity' => $stockQuantity,
                'exportedQuantity' => $exportedQuantity,
                'realityQuantity' => $realityQuantity,
                'checked200' => $checked200,
                'unchecked200' => $unchecked200,
                'stockEndQuantity' => $stockEndQuantity,
                'daysInventory' => $daysInventory,
                'totalQuantityExportPerMonth' => $totalQuantityExportPerMonth,
                'stockMOQ' => $firstStockMOQ, // Thêm giá trị này vào mảng
            ];
        }

        $title = 'Danh sách hàng hóa';
        return view('export/product/fapv-export', compact('data', 'title', 'models', 'listMonthExport'));
    }



    public function title(): string
    {
        return 'FAPV';
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
                $event->sheet->freezePane('C2');
                // Dựng dọc các cột từ M đến AC
                $event->sheet->getStyle('M1:AC1')->getAlignment()->setTextRotation(90);
                $event->sheet->getStyle('A1:AC1')->getAlignment()->setWrapText(true);
                $event->sheet->getSheetView()->setZoomScale(136);
            },
        ];
    }
}
