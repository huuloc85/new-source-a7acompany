<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'key',          // ví dụ: 'view_users'
        'name',         // hiển thị: 'Xem danh sách người dùng'
        'type',         // admin / employee / both
        'module',       // nhóm quyền: schedule, salary, products...
        'display_area', // home / sidebar / both
        'icon',         // icon cho permission
        'url',          // URL path cho FE
        'sort_order',   // thứ tự hiển thị
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission', 'permission_id', 'role_id');
    }

    // 1 Permission có N SidebarItem
    public function sidebarItems()
    {
        return $this->hasMany(SidebarItem::class, 'permission_id', 'id');
    }
}
