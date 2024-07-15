<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryOfficialVVPTimekeeping extends Model
{
    use HasFactory;
    protected $table = 'timekeeping_officials_vvp';
    protected $fillable = [
        'salary_official_vvp_id',
        'timekeeping_date',         //ngày chấm công
        'timekeeping_day',          //số giờ làm ngày
        'timekeeping_night',        //số giờ làm đêm
        'timekeeping_overtime',     //số giờ tăng ca
    ];

    //relationship SalaryOfficial
    public function SalaryOfficialVVP()
    {
        return $this->belongsTo(SalaryOfficialVVP::class, 'salary_official_vvp_id', 'id');
    }
}
