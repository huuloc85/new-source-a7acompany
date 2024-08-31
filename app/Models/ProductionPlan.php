<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionPlan extends Model
{
    use HasFactory;

    protected $table = 'production_plans';

    protected $fillable = [
        'product_id',
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
        'produced_quantity',                                        // Số Lượng Đã SX (PCS)
        'month'                                                     // Tháng
    ];

    public function updateProductionPlanAttributes($data)
    {
        // Kiểm tra và gán giá trị cho các thuộc tính
        $this->production_plan = $data['production_plan'] ?? $this->production_plan;
        $this->product_density = $data['product_density'] ?? $this->product_density;
        $this->packaging_count_per_box = $data['packaging_count_per_box'] ?? $this->packaging_count_per_box;
        $this->products_per_box = $data['products_per_box'] ?? $this->products_per_box;
        $this->cycle = $data['cycle'] ?? $this->cycle;
        $this->cavity_count = $data['cavity_count'] ?? $this->cavity_count;

        // Cập nhật các thuộc tính tính toán
        $this->planned_material = ($this->production_plan * $this->product_density) / 1000;
        $this->box_quantity = $this->products_per_box == 0 ? 0 : $this->production_plan / $this->products_per_box;
        $this->total_packaging = $this->packaging_count_per_box * $this->box_quantity;
        $this->daily_production_plan = ((22 * 3600) / $this->cycle) * $this->cavity_count;
        $this->machine_run_days = $this->daily_production_plan == 0 ? 0 : $this->production_plan / $this->daily_production_plan;

        // Tính toán và cập nhật sản lượng đã sản xuất và số lượng còn lại
        $producedQuantity = DailyQuantity::where('status', 1)
            ->where('product_id', $this->product_id)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('quantity');

        $this->produced_quantity = $producedQuantity;
        $this->remaining_production_quantity = $this->production_plan - $producedQuantity;
        $this->remaining_production_days = $this->daily_production_plan == 0 || $this->remaining_production_quantity == 0 ? 0 : $this->remaining_production_quantity / $this->daily_production_plan;
        $this->save();
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function materialProducts()
    {
        return $this->hasMany(MaterialProduct::class, 'production_plans_id');
    }

    public $materialcolor = ['Natural', 'Gray', 'Black'];
}
