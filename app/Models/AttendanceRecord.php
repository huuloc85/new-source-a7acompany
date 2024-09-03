<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $table = 'attendencerecord'; // Tên bảng

    protected $fillable = [
        'employee_code',
        'datetime',
        'date',
        'time',
        'direction',
        'deviceName',
        'deviceSN',
        'employee_Name',
        'cardNo'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_code', 'code');
    }
}
