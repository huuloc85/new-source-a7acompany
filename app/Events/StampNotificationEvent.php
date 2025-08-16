<?php

namespace App\Events;

use App\Models\SendStamp;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StampNotificationEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public SendStamp $sendStamp,
        public string|int $id
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel('public.stamps.'.$this->id);
    }

    public function broadcastAs(): string
    {
        return 'stamp.created.'.$this->id;
    }

    public function broadcastWith(): array
    {
        return [
            'message' => "Có yêu cầu in tem mới cho sản phẩm {$this->sendStamp->product->name} từ nhân viên <strong>{$this->sendStamp->employee->name}</strong>",
            'recordId' => $this->sendStamp->id,
            'meta' => $this->sendStamp,
            'sent_at' => now()->toISOString(),
        ];
    }
}
