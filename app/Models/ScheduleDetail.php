<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ScheduleDetail extends Pivot
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'schedule_details';

    protected $primaryKey = ['date', 'schedule_id', 'employee_id'];

    protected $fillable = [
        'date',
        'schedule_id',
        'employee_id',
        'is_wc_clean_men',
        'is_wc_clean_women',
        'is_wc_trash',
        'is_eat_room',
        'hnhc',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName($this->table)
            ->logAll()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function attendanceRecords()
    {
        return $this->hasMany(
            AttendanceRecord::class,
            'date',
            'date'
        );
    }

    public function schedules()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function employees()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function scopeDateBetween(Builder $query, $start = null, $end = null): Builder
    {
        if ($start && $end) {
            return $query->whereBetween('date', [Carbon::parse($start), Carbon::parse($end)]);
        }
        if ($start) {
            return $query->where('date', '>=', Carbon::parse($start));
        }
        if ($end) {
            return $query->where('date', '<=', Carbon::parse($end));
        }

        return $query;
    }
}
