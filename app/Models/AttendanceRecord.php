<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $table = 'attendance_records'; // Tên bảng

    // public $incrementing = false;

    public const paginate = 500;

    protected $fillable = [
        'employee_code',
        'datetime',
        'date',
        'time',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_code', 'id');
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
            $query->whereBetween('date', [Carbon::parse($start), Carbon::parse($end)]);
        }

        if ($start) {
            $query->where('date', '>=', Carbon::parse($start));
        }

        if ($end) {
            $query->where('date', '<=', Carbon::parse($end));
        }

        return $query;
    }

    public function scopeTimeBetween(Builder $query, $start = null, $end = null): Builder
    {
        if ($start && $end) {
            $query->whereBetween('time', [Carbon::parse($start), Carbon::parse($end)]);
        }

        if ($start) {
            $query->where('time', '>=', Carbon::parse($start));
        }

        if ($end) {
            $query->where('time', '<=', Carbon::parse($end));
        }

        return $query;
    }

    public function scopeDatetimeBetween(Builder $query, $start = null, $end = null): Builder
    {
        if ($start && $end) {
            $query->whereBetween('datetime', [Carbon::parse($start), Carbon::parse($end)]);
        }

        if ($start) {
            $query->where('datetime', '>=', Carbon::parse($start));
        }

        if ($end) {
            $query->where('datetime', '<=', Carbon::parse($end));
        }

        return $query;
    }
}
