<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const paginate = 10;

    protected $table = 'roles';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'role_name',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
    }

    // relationship employees
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    // search by role_name
    public function scopeSearch($query)
    {
        if ($key = request()->key) {
            $query = $query->where('role_name', 'like', '%'.$key.'%');
        }

        return $query;
    }

    // format date-time
    public function formatTimeDMY($date)
    {
        return date('H:m:s d/m/Y', strtotime($date));
    }
}
