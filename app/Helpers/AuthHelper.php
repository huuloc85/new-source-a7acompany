<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthHelper
{
    public function authSession()
    {
        if (Session::has('auth_user')) {
            return Session::get('auth_user');
        }

        $user = Auth::user();
        Session::put('auth_user', $user);

        return $user;
    }

    public function checkMenuRoleAndPermission($menu)
    {
        if (! Auth::check()) {
            return false;
        }

        $user = Auth::user();

        // Check if menu has no role restriction and user is admin
        if ($menu->data('role') === null && $user->role->role_name === 'admin') {
            return true;
        }

        // If no role and permission restrictions, allow access
        if ($menu->data('permission') === null && $menu->data('role') === null) {
            return true;
        }

        // Check role restrictions
        if ($menu->data('role') !== null) {
            $requiredRoles = explode(',', $menu->data('role'));
            if (in_array($user->role->role_name, $requiredRoles)) {
                return true;
            }
        }

        // Check permission restrictions
        if ($menu->data('permission') !== null) {
            // gọi static method
            return static::hasPermission(
                $user->role->permissions()->pluck('key')->toArray(),
                $menu->data('permission')
            );
        }

        return false;
    }

    // Đổi sang static để gọi tĩnh hợp lệ ở mọi nơi
    public static function hasPermission(array $permissions, string $requiredPermission): bool
    {
        return in_array($requiredPermission, $permissions, true);
    }

    public function checkRolePermission($role, $permission)
    {
        try {
            return $role->permissions()->where('key', $permission)->exists();
        } catch (Exception $e) {
            return false;
        }
    }

    public function demoUserPermission()
    {
        $user = Auth::user();

        return $user && $user->role->role_name === 'demo_admin';
    }
}
