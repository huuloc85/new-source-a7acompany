<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CelenderDetailWCClean extends Model
{

    use HasFactory;

    //paginate
    public const paginate = 10;

    //table
    protected $table = 'celender_detail_wc_clean';

    //fillable
    protected $fillable = [
        'employee_id',
        'celender_id',
        'day1',
        'day2',
        'day3',
        'day4',
        'day5',
        'day6',
        'day7',
        'day8',
        'day9',
        'day10',
        'day11',
        'day12',
        'day13',
        'day14',
        'day15',
        'day16',
        'day17',
        'day18',
        'day19',
        'day20',
        'day21',
        'day22',
        'day23',
        'day24',
        'day25',
        'day26',
        'day27',
        'day28',
        'day29',
        'day30',
        'day31',
    ];

    //relationship celender
    function celender()
    {
        return $this->belongsTo(Celender::class);
    }

    //relationship role
    function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
