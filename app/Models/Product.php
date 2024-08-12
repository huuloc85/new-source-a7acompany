<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    //paginate
    public const paginate = 10;

    //table
    protected $table = "products";

    //fillable
    protected $fillable = [
        'code',                //Mã sản phẩm
        'name',                //Tên sản phẩm
        'quantity',            //Sản lượng
        // 'quantityCaTon',       //Số lượng thùng caton
        'moldSize',            //Kích thước khuôn
        'CAV',                 //Số cái/shot
        'cycle',               //Chu kì s/shot
        // 'planTime',            //Dự định thời gian hoạt động thiết bị(ngày/tháng)
        // 'realTime',            //Thực tế thời gian hoạt động thiết bị(ngày/tháng)
        'FAPV',                //Công ty A
        'FASV',                //Công ty B
        'FAVV',                //Công ty C
        'binCode',             //Mã thùng
        'quanEntityBin',       //Số lượng con/thùng
        // 'stockQuan',           //Số lượng tồn đầu kì
        // 'stockQuan200',        //Số lượng tồn đầu hàng 200%
        // 'prorealityQuan',      //Tổng số lượng sản xuất thực tế (nhân viên nhập vào hằng ngày)
        // 'exportedQuan',        //Tổng số lượng đã xuất(nhân viên nhập số lượng xuất hằng ngày đã kiểm 200%->xuất)->tổng(status3)
        // 'importedQuan',        //Tổng số lượng đã nhập (200%)
        // 'produceQuan',         //Tổng số lượng sản xuất (Tổng số lượng sản xuất thực tế(prorealityQuan) + Số lượng tồn đầu kì(stockQuan))
        // 'checkedQuan',         //Tổng số lượng đã kiểm (200%) (số lượng nhập hàng hằng ngày + Tồn đầu hàng 200%)
        // 'stockTotal',          //Tổng số lượng tồn kho (Số lượng sản xuất(produceQuan) - số lượng đã xuất(exportedQuan))
        // 'uncheckedQuan',       //Tổng số lượng chưa kiểm (200%)(Số lượng tồn kho (stockTotal) - Tổng số lượng đã kiểm(checkedQuan))
        // 'stockDayTotal',       //Tổng số ngày tồn kho(Tổng số lượng tồn(stockTotal)\(Sản lượng(quantity)\24))
        // 'monthUpdate',         //Thời gian cập nhật số lượng tồn đầu hàng 200% lưu theo dạng 03/2024
    ];

    public $models = ['1/6-300K5W', '1/6-300K6S', '1/6-450K5W', '1/6-450K6S', '1/8-300K5W', 'VVP 01', '1/12-150K5S', '1/16-150K5S', '1/24-150K5S', '1/12-300K5S'];
    public $modelSizes = ['350×350', '500×500', '550×550', '700×700', '800×700'];

    //relationship DailyQuantity
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

    //relationship TotalDailyQuantity
    public function TotalDailyQuantities()
    {
        return $this->hasMany(TotalDailyQuantity::class, 'product_id', 'id');
    }

    //relationship TotalMonthQuantity
    public function TotalMonthQuantities()
    {
        return $this->hasMany(TotalMonthQuantity::class, 'product_id', 'id');
    }

    //relationship StorageProduct
    public function StorageProducts()
    {
        return $this->hasMany(StorageProduct::class, 'product_id', 'id');
    }

    //relationship HistoryPrints
    public function historyPrints()
    {
        return $this->hasMany(HistoryPrint::class, 'product_id', 'id');
    }

    //relationship productionPlan
    public function productionPlans()
    {
        return $this->hasMany(ProductionPlan::class);
    }

    public function materialProducts()
    {
        return $this->hasMany(MaterialProduct::class);
    }

    //search by name
    public function scopeName($query, $request)
    {
        if ($request->has('name')) {
            return $query->where('name', 'like', '%' . $request->name . '%');
        }
        return $query;
    }

    //search by code
    public function scopeCode($query, $request)
    {
        if ($request->has('code')) {
            return $query->where('code', 'like', '%' . $request->code . '%');
        }
        return $query;
    }

    //search by name
    public function scopeMoldSize($query, $request)
    {
        if ($request->has('moldSize')) {
            return $query->where('moldSize', 'like', '%' . $request->moldSize . '%');
        }
        return $query;
    }

    //search by code
    public function scopeBinCode($query, $request)
    {
        if ($request->has('binCode')) {
            return $query->where('binCode', 'like', '%' . $request->binCode . '%');
        }
        return $query;
    }
}
