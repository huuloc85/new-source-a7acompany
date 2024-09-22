<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CelenderDetailWC extends Model
{
    use HasFactory;

        //paginate
        public const paginate = 10;

        //table
        protected $table = 'celender_detail_wc';
    
    //fillable
    protected $fillable = [
        'employee_id',
        'celender_id',
        'day1',
        'day2',
        'day3',
        'day4',
        'day5',
    ];

    //relationship celender
    function celender(){
        return $this->belongsTo(Celender::class);
    }

    //relationship role
    function employee(){
        return $this->belongsTo(Employee::class);
    }
}
