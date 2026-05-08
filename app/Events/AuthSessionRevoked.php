<?php

namespace App\Events;

use App\Models\Employee;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuthSessionRevoked implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Employee $user,
        public string $sessionId,
    ) {}

    public static function channelNameFor(Employee $user): string
    {
        return 'auth.user.'.hash('sha256', (string) $user->id);
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel(self::channelNameFor($this->user));
    }

    public function broadcastAs(): string
    {
        return 'auth.session-revoked';
    }

    public function broadcastWith(): array
    {
        return [
            'session_id' => $this->sessionId,
            'code' => 'LOGGED_IN_ELSEWHERE',
            'message' => 'Tài khoản của bạn đã được đăng nhập ở nơi khác.',
        ];
    }
}
