<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Celender extends Model
{
    use HasFactory;

    //paginate
    public const paginate = 10;

    //fillable
    protected $fillable = [
        'title',
        'date',
    ];

    public static $weeks = [
        'Thứ Hai' => 'monday',
        'Thứ Ba' => 'tuesday',
        'Thứ Tư' => 'wednesday',
        'Thứ Năm' => 'thursday',
        'Thứ Sáu' => 'friday',
        'Thứ Bảy' => 'saturday',
        'Chủ Nhật' => 'sunday',
    ];

    //relationship celenderDetailsHNHC
    function celenderDetailsHNHC()
    {
        return $this->hasMany(CelenderDetailHNHC::class, 'celender_id', 'id');
    }

    //relationship celenderDetailsHNHC
    function celenderDetailsEatroom()
    {
        return $this->hasMany(CelenderDetailEatroom::class, 'celender_id', 'id');
    }

    //relationship celenderDetailsHNHC
    function celenderDetailsWC()
    {
        return $this->hasMany(CelenderDetailWC::class, 'celender_id', 'id');
    }

    function celenderDetailsWCCleanWomen()
    {
        return $this->hasMany(CelenderDetailWCCleanMen::class, 'celender_id', 'id');
    }

    function celenderDetailsWCCleanMen()
    {
        return $this->hasMany(CelenderDetailWCCleanMen::class, 'celender_id', 'id');
    }

    //search by name
    public function scopeName($query, $request)
    {
        if ($request->has('key')) {
            return $query->where('title', 'like', '%' . $request->key . '%');
        }
        return $query;
    }

    //format date-time
    public function formatTimeDMY($date)
    {
        return date('d/m/Y', strtotime($date));
    }

    //format date
    public function formatTimeDate($date)
    {
        return date('d', strtotime($date));
    }

    //format date-time
    public function formatTimeYMD($date)
    {
        return date('Y-m-d', strtotime($date));
    }

    //return day of week
    public function dayOfWeek($date)
    {
        $dayofweek = $this->formatTimeDMY($date);
        $dayofweek = date('l', strtotime($date));
        $result = "";
        switch ($dayofweek) {
            case 'Monday':
                $result = 'T2';
                break;
            case 'Tuesday':
                $result = 'T3';
                break;
            case 'Wednesday':
                $result = 'T4';
                break;
            case 'Thursday':
                $result = 'T5';
                break;
            case 'Friday':
                $result = 'T6';
                break;
            case 'Saturday':
                $result = 'T7';
                break;
            case 'Sunday':
                $result = 'CN';
                break;
        }
        return $result;
    }

    //check celender
    public function checkCelender($date, $celender, $increases)
    {
        $dayofweek = $this->formatTimeDMY($date);
        $dayofweek = strtolower(date('l', strtotime($date)));
        if ($this->checkIncrease($date, $celender, $increases)) {
            return "Tăng cường";
        } else {
            if ($celender->$dayofweek != 1) {
                return "Nghỉ";
            }
            return "Làm";
        }
    }

    //check tang ca
    public function checkIncrease($date, $celender, $increases)
    {
        $check = false;
        foreach ($increases as $increase) {
            if ($increase->employee_id == $celender->id && $increase->date == $this->formatTimeYMD($date)) {
                $check = true;
                break;
            }
        }
        return $check;
    }
}
