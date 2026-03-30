<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('send-stamp-channel', function ($user) {
    if ($user) {
        return true;
    }
});

// Feedback: employee nhận thông báo khi admin reply
Broadcast::channel('feedback.employee.{employeeId}', function ($user, $employeeId) {
    return $user->id === $employeeId;
});

// Feedback: admin nhận thông báo khi employee gửi góp ý mới
Broadcast::channel('feedback.admin', function ($user) {
    if (! $user || ! $user->role) {
        return false;
    }
    $roleName = strtolower(trim($user->role->role_name));

    return in_array($roleName, ['super admin', 'admin', 'co admin'], true);
});
