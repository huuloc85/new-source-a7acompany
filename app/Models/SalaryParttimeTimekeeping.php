<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryParttimeTimekeeping extends Model
{
    use HasFactory;

    //table
    protected $table = "timekeeping_parttimes";

    //paginate
    public const paginate = 10;

    protected $fillable = [
        'salary_parttime_id',
        'timekeeping_date',         //ngày chấm công
        'timekeeping_day',          //số giờ làm ngày
        'timekeeping_night',        //số giờ làm đêm
        'timekeeping_overtime',     //số giờ tăng ca
    ];

    //relationship SalaryParttime
    public function SalaryParttime()
    {
        return $this->belongsTo(SalaryParttime::class, 'salary_parttime_id', 'id');
    }
}
