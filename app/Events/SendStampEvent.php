<?php

namespace App\Events;

use App\Models\Employee;
use App\Models\SendStamp;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendStampEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sendStamp;

    public $user;

    public function __construct(SendStamp $sendStamp, Employee $user)
    {
        $this->sendStamp = $sendStamp;
        $this->user = $user;
    }

    public function broadcastOn()
    {
        // Phát trên kênh riêng cho mỗi user
        return new Channel('user.'.$this->user->id);
    }

    public function broadcastWith()
    {
        return [
            'message' => "Có yêu cầu in tem mới cho sản phẩm {$this->sendStamp->product->name} từ nhân viên <strong>{$this->sendStamp->employee->name}</strong>",
            'recordId' => $this->sendStamp->id, // Thêm ID của bản ghi
        ];
    }
}
