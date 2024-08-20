<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $table = 'mcc'; // Tên bảng

    protected $fillable = [
        'auth_d',
        'auth_t',
        'direction',
        'dv_name',
        'dv_seri_no',
        'per_name',
        'card_no',
        'employee_code'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_code', 'code');
    }
}
