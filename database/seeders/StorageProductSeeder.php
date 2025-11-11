<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Product;
use App\Models\StorageProduct;
use Illuminate\Database\Seeder;

class StorageProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kiểm tra xem có products và employees không
        $products = Product::take(5)->get();
        $employees = Employee::take(3)->get();

        if ($products->isEmpty() || $employees->isEmpty()) {
            $this->command->info('Cần có ít nhất 1 product và 1 employee để tạo storage products.');

            return;
        }

        // Tạo một số storage products mẫu
        $storageProducts = [
            [
                'product_id' => $products->first()->id,
                'lot' => 'LOT001-2024',
                'employee_id' => $employees->first()->id,
                'bin' => 1,
                'quantity' => 150,
                'barcode' => 'SP001-2024-LOT001',
            ],
            [
                'product_id' => $products->count() > 1 ? $products->get(1)->id : $products->first()->id,
                'lot' => 'LOT002-2024',
                'employee_id' => $employees->count() > 1 ? $employees->get(1)->id : $employees->first()->id,
                'bin' => 2,
                'quantity' => 200,
                'barcode' => 'SP002-2024-LOT002',
            ],
            [
                'product_id' => $products->count() > 2 ? $products->get(2)->id : $products->first()->id,
                'lot' => 'LOT003-2024',
                'employee_id' => $employees->count() > 2 ? $employees->get(2)->id : $employees->first()->id,
                'bin' => 3,
                'quantity' => 75,
                'barcode' => 'SP003-2024-LOT003',
            ],
            [
                'product_id' => $products->count() > 3 ? $products->get(3)->id : $products->first()->id,
                'lot' => 'LOT004-2024',
                'employee_id' => $employees->first()->id,
                'bin' => 4,
                'quantity' => 0, // Sản phẩm hết hàng
                'barcode' => 'SP004-2024-LOT004',
            ],
            [
                'product_id' => $products->count() > 4 ? $products->get(4)->id : $products->first()->id,
                'lot' => 'LOT005-2024',
                'employee_id' => $employees->first()->id,
                'bin' => 5,
                'quantity' => 300,
                'barcode' => 'SP005-2024-LOT005',
            ],
        ];

        foreach ($storageProducts as $data) {
            StorageProduct::create($data);
        }

        $this->command->info('Đã tạo '.count($storageProducts).' storage products mẫu.');
    }
}
