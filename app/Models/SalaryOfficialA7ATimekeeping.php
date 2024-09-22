<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryOfficialA7ATimekeeping extends Model
{
    use HasFactory;

    //table
    protected $table = "timekeeping_officials_a7a";

    protected $fillable = [
        'salary_official_a7a_id',
        'timekeeping_date',         //ngày chấm công
        'timekeeping_day',          //số giờ làm ngày
        'timekeeping_night',        //số giờ làm đêm
        'timekeeping_overtime',     //số giờ tăng ca
    ];

    //relationship SalaryOfficialA7A
    public function SalaryOfficialA7A()
    {
        return $this->belongsTo(SalaryOfficialA7A::class, 'salary_official_a7a_id', 'id');
    }
}
