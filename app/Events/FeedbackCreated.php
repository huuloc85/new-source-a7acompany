<?php

namespace App\Events;

use App\Models\Feedback;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event: Employee gửi góp ý mới.
 * Channel: feedback.admin (public channel cho tất cả admin)
 * FE listen: feedback.created
 */
class FeedbackCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Feedback $feedback;

    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
    }

    /**
     * Broadcast trên channel chung cho admin
     */
    public function broadcastOn(): Channel
    {
        return new Channel('feedback.admin');
    }

    public function broadcastAs(): string
    {
        return 'feedback.created';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->feedback->id,
            'employee_id' => $this->feedback->employee_id,
            'type' => $this->feedback->type,
            'subject' => $this->feedback->subject,
            'status' => $this->feedback->status,
            'created_at' => $this->feedback->created_at?->toISOString(),
        ];
    }
}
