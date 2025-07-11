<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncAttendance extends Command
{
    protected $signature = 'attendance:sync';

    protected $description = 'Gọi API để đồng bộ dữ liệu chấm công từ thiết bị';

    public function handle()
    {
        try {
            $url = config('app.url').'/api/acs-events/today';

            $this->info("🔁 Gọi API: $url");
            // Log::info("🔁 Gọi API: $url");

            $response = Http::get($url);

            if ($response->successful()) {
                $data = $response->json();
                $msg = '✅ Đồng bộ thành công. Total: '.($data['total'] ?? 0);

                $this->info($msg);
                // Log::info($msg);
            } else {
                $msg = '❌ Gọi API thất bại: '.$response->status();
                $this->error($msg);
                // Log::error($msg);

                // Log::error('Response body: ' . $response->body());
            }
        } catch (\Exception $e) {
            $errMsg = '❌ Exception: '.$e->getMessage();
            $this->error($errMsg);
            // Log::error($errMsg);
        }

        return 0;
    }
}
