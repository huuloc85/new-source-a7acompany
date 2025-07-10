<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Schedule extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'schedules';

    protected $fillable = [
        'title',
        'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName($this->table)
            ->logAll()
            ->dontSubmitEmptyLogs();
    }

    public function scheduleDetails()
    {
        return $this->hasMany(ScheduleDetail::class, 'schedule_id');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'schedule_details', 'schedule_id', 'employee_id')
            ->withPivot(['date', 'is_wc_clean_men', 'is_wc_clean_women', 'is_wc_trash', 'is_eat_room', 'hnhc'])
            ->withTimestamps();
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
