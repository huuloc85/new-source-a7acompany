<?php

namespace App\Events;

use App\Models\Feedback;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event: Admin đã phản hồi góp ý của Employee.
 * Channel: feedback.employee.{employee_id}
 * FE listen: feedback.replied
 */
class FeedbackReplied implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Feedback $feedback;

    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
    }

    /**
     * Channel riêng cho employee đã gửi feedback
     */
    public function broadcastOn(): Channel
    {
        return new Channel('feedback.employee.'.$this->feedback->employee_id);
    }

    public function broadcastAs(): string
    {
        return 'feedback.replied';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->feedback->id,
            'subject' => $this->feedback->subject,
            'type' => $this->feedback->type,
            'status' => $this->feedback->status,
            'admin_reply' => $this->feedback->admin_reply,
            'replied_by' => $this->feedback->replied_by,
            'replied_at' => $this->feedback->replied_at?->toISOString(),
        ];
    }
}
