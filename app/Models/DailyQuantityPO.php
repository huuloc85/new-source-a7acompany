<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyQuantityPO extends Model
{
    use HasFactory;

    protected $table = "dailyquantities_po";

    //fillable
    protected $fillable = [
        'product_id',                 //ID của sản phẩm
        'quantity',                   //Số lượng cần nhập
        'status',                     //Trạng thái nhập (*)
        'date',                       //Ngày nhập số lượng sử dụng cột timestamp để lấy thời gian nhạp số lượng
        'employee_id'                 //Người nhập số lượng
    ];

    //relationship employee
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    //relationship product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
