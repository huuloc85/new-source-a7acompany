<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncAttendanceDeviceEmployees extends Command
{
    protected $signature = 'attendance:sync-employees {--limit=}';

    protected $description = 'Đồng bộ nhân viên trong DB local vào máy chấm công nội bộ';

    private const LAST_SYNC_CACHE_KEY = 'acs_employee_sync_last_created_at';

    public function handle(): int
    {
        if (! $this->hasDeviceConfig()) {
            $this->warn('Thiếu ACS_DEVICE_IP, ACS_USERNAME hoặc ACS_PASSWORD, bỏ qua đồng bộ nhân viên.');

            return self::SUCCESS;
        }

        $limit = (int) ($this->option('limit') ?: 0);
        $synced = 0;
        $skipped = 0;
        $failed = 0;

        $lastSyncedCreatedAt = Cache::get(self::LAST_SYNC_CACHE_KEY);

        if (! $lastSyncedCreatedAt) {
            Cache::forever(self::LAST_SYNC_CACHE_KEY, now()->toDateTimeString());
            $this->info('Khởi tạo mốc đồng bộ nhân viên. Lần chạy sau sẽ chỉ lấy nhân viên mới.');

            return self::SUCCESS;
        }

        $query = Employee::query()
            ->select(['id', 'name', 'created_at'])
            ->whereNotIn('role_id', [15, 21, 22, 1])
            ->where('created_at', '>', $lastSyncedCreatedAt)
            ->orderBy('created_at')
            ->orderBy('id');

        $latestCreatedAt = $lastSyncedCreatedAt;

        $handleEmployees = function ($employees) use (&$latestCreatedAt, &$synced, &$skipped, &$failed) {
            foreach ($employees as $employee) {
                $result = $this->syncEmployee($employee->toArray());

                if ($result === 'synced') {
                    $synced++;
                } elseif ($result === 'failed') {
                    $failed++;

                    return false;
                } else {
                    $skipped++;
                }

                $latestCreatedAt = $employee->created_at?->toDateTimeString() ?: $latestCreatedAt;
            }

            return true;
        };

        if ($limit > 0) {
            $employees = $query->limit($limit)->get();
            $handleEmployees($employees);
        } else {
            $query->chunk(100, function ($employees) use ($handleEmployees) {
                return $handleEmployees($employees);
            });
        }

        if ($latestCreatedAt !== $lastSyncedCreatedAt) {
            Cache::forever(self::LAST_SYNC_CACHE_KEY, $latestCreatedAt);
        }

        $this->info("Đồng bộ nhân viên hoàn tất. Synced: {$synced}, skipped: {$skipped}, failed: {$failed}.");

        return self::SUCCESS;
    }

    private function hasDeviceConfig(): bool
    {
        return (bool) config('acs.device_ip')
            && (bool) config('acs.username')
            && (bool) config('acs.password');
    }

    private function syncEmployee(array $employee): string
    {
        $employeeId = $employee['id'] ?? null;
        $employeeName = $employee['name'] ?? null;

        if (! $employeeId || ! $employeeName) {
            return 'skipped';
        }

        $payloadHash = sha1(json_encode([
            'id' => (string) $employeeId,
            'name' => (string) $employeeName,
            'created_at' => $employee['created_at'] ?? null,
        ]));

        $cacheKey = 'acs_employee_synced:'.(string) $employeeId;

        if (Cache::get($cacheKey) === $payloadHash) {
            return 'skipped';
        }

        try {
            $this->postEmployeeToDevice((string) $employeeId, (string) $employeeName);
            Cache::forever($cacheKey, $payloadHash);

            return 'synced';
        } catch (\Throwable $e) {
            Log::error('Attendance device employee sync failed', [
                'employee_id' => $employeeId,
                'message' => $e->getMessage(),
            ]);

            return 'failed';
        }
    }

    private function postEmployeeToDevice(string $employeeId, string $employeeName): void
    {
        $deviceIp = config('acs.device_ip');
        $username = config('acs.username');
        $password = config('acs.password');
        $url = "http://{$deviceIp}/ISAPI/AccessControl/UserInfo/Record?format=json";

        $response = Http::withDigestAuth($username, $password)
            ->timeout(10)
            ->post($url, [
                'UserInfo' => [
                    'employeeNo' => $employeeId,
                    'name' => $employeeName,
                    'userType' => 'normal',
                    'Valid' => [
                        'enable' => true,
                        'beginTime' => '2026-01-01T00:00:00',
                        'endTime' => '2036-01-01T23:59:59',
                        'timeType' => 'local',
                    ],
                ],
            ]);

        if ($response->successful()) {
            return;
        }

        $body = $response->body();

        if (str_contains(strtolower($body), 'exist')) {
            return;
        }

        throw new \RuntimeException("Device HTTP {$response->status()}: {$body}");
    }
}
