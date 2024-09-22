<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialProduct extends Model
{
    use HasFactory;

    // Tên bảng
    protected $table = 'material_product';

    // Các cột trong bảng
    protected $fillable = [
        'product_id',
        'production_plans_id',
        'quantity',
        'real_quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Ví dụ: Một MaterialProduct thuộc về một ProductionPlan
    public function productionPlan()
    {
        return $this->belongsTo(ProductionPlan::class, 'production_plans_id');
    }
}
