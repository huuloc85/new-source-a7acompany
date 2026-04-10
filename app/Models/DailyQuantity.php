<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyQuantity extends Model
{
    use HasFactory;

    // paginate
    public const paginate = 10;

    // table
    protected $table = 'daily_quantities';

    // fillable
    protected $fillable = [
        'product_id',                 // ID của sản phẩm
        'quantity',                   // Số lượng cần nhập
        'status',                     // Trạng thái nhập (*)
        'shift',                      // Ca làm việc (Ca 1, Ca 2)
        'date',                     // Ngày nhập số lượng sử dụng cột timestamp để lấy thời gian nhạp số lượng
        'employee_id',              // Người nhập số lượng
    ];

    // relationship employee
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    // relationship product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
