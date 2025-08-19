<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SidebarItem extends Model
{
    protected $fillable = [
        'permission_id',
        'key',
        'title',
        'icon',
        'path',
    ];

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }
}
