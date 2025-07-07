<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $table = 'attendencerecord'; // Tên bảng

    // public $incrementing = false;

    public const paginate = 500;

    protected $fillable = [
        'employee_code',
        'datetime',
        'date',
        'time',
        'direction',
        'deviceName',
        'deviceSN',
        'employee_Name',
        'cardNo',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_code', 'code');
    }

    public static function getDayOfWeekMapping()
    {
        return [
            'Monday' => 'Thứ Hai',
            'Tuesday' => 'Thứ Ba',
            'Wednesday' => 'Thứ Tư',
            'Thursday' => 'Thứ Năm',
            'Friday' => 'Thứ Sáu',
            'Saturday' => 'Thứ Bảy',
            'Sunday' => 'Chủ Nhật',
        ];
    }

    public function scopeDateBetween(Builder $query, $start = null, $end = null): Builder
    {
        if ($start && $end) {
            $query->whereBetween('date', [$start, $end]);
        }

        if ($start) {
            $query->where('date', '>=', $start);
        }

        if ($end) {
            $query->where('date', '<=', $end);
        }

        return $query;
    }

    public function scopeTimeBetween(Builder $query, $start = null, $end = null): Builder
    {
        if ($start && $end) {
            $query->whereBetween('time', [$start, $end]);
        }

        if ($start) {
            $query->where('time', '>=', $start);
        }

        if ($end) {
            $query->where('time', '<=', $end);
        }

        return $query;
    }

    public function scopeDatetimeBetween(Builder $query, $start = null, $end = null): Builder
    {
        if ($start && $end) {
            $query->whereBetween('datetime', [$start, $end]);
        }

        if ($start) {
            $query->where('datetime', '>=', $start);
        }

        if ($end) {
            $query->where('datetime', '<=', $end);
        }

        return $query;
    }
}
