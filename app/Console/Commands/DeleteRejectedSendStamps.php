<?php

namespace App\Console\Commands;

use App\Models\SendStamp;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteRejectedSendStamps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendstamp:delete-rejected';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Xoá bản ghi SendStamp có status reject quá 1 tiếng';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deletedRecords = SendStamp::where('status', 'rejected')
            // xoá sau 1 tiếng
            ->where('updated_at', '<', Carbon::now()->subHour(1))
            ->delete();

        $this->info("Đã xoá {$deletedRecords} bản ghi bị rejected quá 1 tiếng.");
    }
}
