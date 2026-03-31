<?php

namespace App\Events;

use App\Models\Celender;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event: Admin thêm lịch làm việc mới.
 * Channel: employee.notifications (public channel cho tất cả nhân viên)
 * FE listen: schedule.created
 */
class ScheduleCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Celender $schedule;

    public function __construct(Celender $schedule)
    {
        $this->schedule = $schedule;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('employee.notifications');
    }

    public function broadcastAs(): string
    {
        return 'schedule.created';
    }

    public function broadcastWith(): array
    {
        return [
            'type' => 'schedule',
            'id' => $this->schedule->id,
            'title' => $this->schedule->title,
            'date' => $this->schedule->date,
        ];
    }
}
