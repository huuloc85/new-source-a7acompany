<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalMonthQuantity extends Model
{
    use HasFactory;

    //paginate
    public const paginate = 10;

    //status
    private $status = [
        1, //số lượng sản xuất
        2, //số lượng nhập hàng
        3, //số lượng xuất hàng
        4, //số lượng tồn đầu kì
        5, //số lượng tồn đầu kì 200%
        6, //số lượng hàng lỗi 200%
        7, //MOQ của từng tháng
        8, // số lượng xuất hàng po

    ];

    //table
    protected $table = "totalmonthquantities";

    //fillable
    protected $fillable = [
        'product_id',                                           //ID của sản phẩm
        'status',                                               //Trạng thái nhập (*)
        'month',                                                //Tháng lưu theo dạng string tháng-năm (03-2024)
        'totalQuan',                                            //Tổng số lượng/ngày
    ];

    //relationship product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
