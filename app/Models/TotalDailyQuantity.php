<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalDailyQuantity extends Model
{
    use HasFactory;

    //paginate
    public const paginate = 10;

    //table
    protected $table = "totaldailyquantities";

    //fillable
    protected $fillable = [
        'product_id',
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
