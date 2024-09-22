<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;

    //paginate
    public const paginate = 10;

    //table
    protected $table = "roles";

    //filds
    protected $fillable = [
        'id',
        'role_name',
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
            $query = $query->where('role_name', 'like', '%' . $key . '%');
        }
        return $query;
    }

    //format date-time
    public function formatTimeDMY($date)
    {
        return date('H:m:s d/m/Y', strtotime($date));
    }
}
