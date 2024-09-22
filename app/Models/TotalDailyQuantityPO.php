<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalDailyQuantityPO extends Model
{
    use HasFactory;

    protected $table = "totaldailyquantities_po";

    //fillable
    protected $fillable = [
        'product_id',                                          //ID của sản phẩm
        'status',                                              //Trạng thái nhập (*)
        'date',                                                //Ngày
        'totalQuan',                                           //Tổng số lượng/ngày
    ];

    //relationship product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
