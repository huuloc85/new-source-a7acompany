<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionPlan extends Model
{
    use HasFactory;

    protected $table = 'production_plans';

    protected $fillable = [
        'product_id',
        'material_name',                                            // Tên Nguyên Vật Liệu
        'production_plan',                                          // Kế Hoạch Sản Xuất (PCS)
        'planned_material',                                         // Dự Định Vật Liệu (KG)
        'packaging_type',                                           // Loại Bao Bì
        'packaging_count_per_box',                                  // Số Bao Bì/Thùng
        'total_packaging',                                          // Tổng Bao Bì
        'box_type',                                                 // Loại Thùng
        'products_per_box',                                         // Sản Phẩm/Thùng
        'box_quantity',                                             // Số Lượng Thùng
        'product_density',                                          // Tỷ Trọng Sản Phẩm (G)
        'daily_production_plan',                                    // Kế Hoạch SX/Ngày
        'cavity_count',                                             // Số Cavity
        'cycle',                                                    // Chu Kỳ
        'ton',                                                      // Tấn
        'machine',                                                  // Máy
        'machine_run_days',                                         // Số Ngày Chạy Máy
        'remaining_production_days',                                // Số Ngày Còn SX (Ngày)
        'remaining_production_quantity',                            // Số Lượng Còn SX (PCS)
        'produced_quantity'                                         // Số Lượng Đã SX (PCS)
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
