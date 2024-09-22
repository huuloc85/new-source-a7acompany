<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryManager extends Model
{
    use HasFactory;

    //paginate
    public const paginate = 10;

    protected $table = "salaries_manager";

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'total',
    ];

    //format date-time
    public function formatTimeDMY($date)
    {
        return date('d/m/Y', strtotime($date));
    }

    //format date-month
    public function formatDateDMY($date)
    {
        return date('d/m', strtotime($date));
    }

    //relationship salaryOfficials
    public function salaryOfficials()
    {
        return $this->hasMany(SalaryOfficial::class, 'salaries_manager_id', 'id');
    }

    //search by name
    public function scopeName($query, $request)
    {
        if ($request->has('key')) {
            return $query->where('title', 'like', '%' . $request->key . '%');
        }
        return $query;
    }
}
