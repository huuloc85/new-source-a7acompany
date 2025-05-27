<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    // paginate
    public const paginate = 10;

    // table
    protected $table = 'products';

    // fillable
    protected $fillable = [
        'code',                // Mã sản phẩm
        'name',                // Tên sản phẩm
        'quantity',            // Sản lượng
        'moldSize',            // Kích thước khuôn
        'CAV',                 // Số cái/shot
        'cycle',               // Chu kì s/shot
        'FAPV',                // Công ty A
        'FASV',                // Công ty B
        'FAVV',                // Công ty C
        'binCode',             // Mã thùng
        'quanEntityBin',       // Số lượng con/thùng
    ];

    public $models = ['1/6-300K5W', '1/6-300K6S', '1/6-450K5W', '1/6-450K6S', '1/8-300K5W', 'VVP 01', '1/12-150K5S', '1/16-150K5S', '1/24-150K5S', '1/12-300K5S'];

    public $modelSizes = ['350×350', '500×500', '550×550', '700×700', '800×700'];

    public $totalQuanDateCa1 = [];

    public $totalQuanDateCa2 = [];

    public $totalQuanDateCheck200 = [];

    public $totalQuanDateError200 = [];

    public $totalQuanDateExport = [];

    public $totalQuanDateError = [];

    const STATUS_PRODUCE = 1;

    const STATUS_CHECK200 = 2;

    const STATUS_EXPORT = 3;

    const STATUS_INVENTORY = 4;

    const STATUS_INVENTORY_CHECK200 = 5;

    const STATUS_ERROR = 6;

    const STATUS_MOQ = 7;

    const STATUS_TOTAL_DAILY_PO = 8;

    // Hoặc nếu có nhiều trạng thái hơn:
    const STATUS_PENDING = 2;

    const STATUS_ARCHIVED = 3;

    // relationship DailyQuantity
    public function DailyQuantities()
    {
        return $this->hasMany(DailyQuantity::class, 'product_id', 'id');
    }

    public function DailyQuantitiesPO()
    {
        return $this->hasMany(DailyQuantityPO::class, 'product_id', 'id');
    }

    public function checkEmployees()
    {
        return $this->hasMany(CheckEmployee::class, 'product_id', 'id');
    }

    // relationship TotalDailyQuantity
    public function TotalDailyQuantities()
    {
        return $this->hasMany(TotalDailyQuantity::class, 'product_id', 'id');
    }

    // relationship TotalMonthQuantity
    public function TotalMonthQuantities()
    {
        return $this->hasMany(TotalMonthQuantity::class, 'product_id', 'id');
    }

    // relationship StorageProduct
    public function StorageProducts()
    {
        return $this->hasMany(StorageProduct::class, 'product_id', 'id');
    }

    public function sendStamps()
    {
        return $this->hasMany(SendStamp::class, 'product_id', 'id');
    }

    // relationship productionPlan
    public function productionPlans()
    {
        return $this->hasMany(ProductionPlan::class);
    }

    public function materialProducts()
    {
        return $this->hasMany(MaterialProduct::class);
    }

    // search by name
    public function scopeName($query, $request)
    {
        if ($request->has('name')) {
            return $query->where('name', 'like', '%'.$request->name.'%');
        }

        return $query;
    }

    // search by code
    public function scopeCode($query, $request)
    {
        if ($request->has('code')) {
            return $query->where('code', 'like', '%'.$request->code.'%');
        }

        return $query;
    }

    // search by name
    public function scopeMoldSize($query, $request)
    {
        if ($request->has('moldSize')) {
            return $query->where('moldSize', 'like', '%'.$request->moldSize.'%');
        }

        return $query;
    }

    // search by code
    public function scopeBinCode($query, $request)
    {
        if ($request->has('binCode')) {
            return $query->where('binCode', 'like', '%'.$request->binCode.'%');
        }

        return $query;
    }
}
