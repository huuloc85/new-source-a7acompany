<?php

namespace App\Events;

use App\Models\SalaryManager;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event: Admin thêm bảng lương mới.
 * Channel: employee.notifications (public channel cho tất cả nhân viên)
 * FE listen: salary.created
 */
class SalaryCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public SalaryManager $salary;

    public function __construct(SalaryManager $salary)
    {
        $this->salary = $salary;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('employee.notifications');
    }

    public function broadcastAs(): string
    {
        return 'salary.created';
    }

    public function broadcastWith(): array
    {
        return [
            'type' => 'salary',
            'id' => $this->salary->id,
            'title' => $this->salary->title,
            'start_date' => $this->salary->start_date,
            'end_date' => $this->salary->end_date,
        ];
    }
}
