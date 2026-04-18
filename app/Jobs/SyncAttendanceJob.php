<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\SalaryCalculationService;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncAttendanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Không giới hạn thời gian chạy Job
     */
    public $timeout = 0;

    protected $salaryManagerId;
    protected $company;

    /**
     * Create a new job instance.
     */
    public function __construct(int $salaryManagerId, string $company)
    {
        $this->salaryManagerId = $salaryManagerId;
        $this->company = $company;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        
        try {
            $service = new SalaryCalculationService($this->company);
            $service->syncAttendanceData($this->salaryManagerId);
            Log::info("SyncAttendanceJob SUCCESS: Completed sync for Salary Manager #{$this->salaryManagerId} - {$this->company}");
        } catch (Throwable $e) {
            Log::error("SyncAttendanceJob ERROR (Salary #{$this->salaryManagerId}): " . $e->getMessage());
            throw $e; // Đẩy vào failed_jobs để retry
        }
    }
}
