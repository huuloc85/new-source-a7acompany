<?php

namespace App\Jobs;

use App\Mail\RequestFormCreatedMail;
use App\Models\RequestForm;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendRequestFormNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $requestForm;

    public $supervisor;

    public $approvalUrl;

    /**
     * Create a new job instance.
     */
    public function __construct(RequestForm $requestForm, $supervisor, string $approvalUrl)
    {
        $this->requestForm = $requestForm;
        $this->supervisor = $supervisor;
        $this->approvalUrl = $approvalUrl;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->supervisor->email)->send(
            new RequestFormCreatedMail($this->requestForm, $this->supervisor, $this->approvalUrl)
        );
    }
}
