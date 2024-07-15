<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryCelender extends Model
{
    use HasFactory;

    //paginate
    public const paginate = 10;

    protected $table = "categories_celender";

    protected $fillable = [
        'name'
    ];

    //relationship employees
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    //search by role_name
    public function scopeSearch($query)
    {
        if ($key = request()->key) {
            $query = $query->where('name', 'like', '%' . $key . '%');
        }
        return $query;
    }

    //format date-time
    public function formatTimeDMY($date)
    {
        return date('H:m:s d/m/Y', strtotime($date));
    }
}
