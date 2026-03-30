<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanupTrashedEmployees extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'employees:cleanup-trash
                            {--days=30 : Số ngày trong thùng rác trước khi xoá vĩnh viễn}
                            {--dry-run : Chỉ hiển thị, không thực sự xoá}';

    /**
     * The console command description.
     */
    protected $description = 'Tự động xoá vĩnh viễn nhân viên đã nghỉ việc (trong thùng rác) sau N ngày';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');
        $dryRun = $this->option('dry-run');

        $cutoffDate = now()->subDays($days);

        $trashedEmployees = Employee::onlyTrashed()
            ->where('deleted_at', '<=', $cutoffDate)
            ->whereNotIn('role_id', [1, 21]) // Bảo vệ Super Admin & Giám đốc
            ->get();

        if ($trashedEmployees->isEmpty()) {
            $this->info("✅ Không có nhân viên nào trong thùng rác quá {$days} ngày.");

            return self::SUCCESS;
        }

        $this->info("📋 Tìm thấy {$trashedEmployees->count()} nhân viên trong thùng rác quá {$days} ngày:");
        $this->newLine();

        $tableData = $trashedEmployees->map(function ($emp) {
            return [
                'ID' => $emp->id,
                'Tên' => $emp->name,
                'Phone' => $emp->phone,
                'Ngày xoá' => $emp->deleted_at->format('d/m/Y H:i'),
                'Số ngày' => $emp->deleted_at->diffInDays(now()).' ngày',
            ];
        })->toArray();

        $this->table(['ID', 'Tên', 'Phone', 'Ngày xoá', 'Số ngày'], $tableData);

        if ($dryRun) {
            $this->warn('⚠️  Chế độ dry-run: Không có nhân viên nào bị xoá.');

            return self::SUCCESS;
        }

        $deletedCount = 0;

        // Tắt FK check để xoá được nhân viên có dữ liệu liên quan
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            foreach ($trashedEmployees as $employee) {
                try {
                    $employee->tokens()->delete();
                    $employee->forceDelete();
                    $deletedCount++;

                    Log::info('Cleanup: Force deleted employee', [
                        'id' => $employee->id,
                        'name' => $employee->name,
                        'deleted_at' => $employee->deleted_at,
                    ]);
                } catch (\Throwable $e) {
                    $this->error("❌ Lỗi xoá {$employee->id}: {$e->getMessage()}");
                    Log::error('Cleanup: Failed to delete employee', [
                        'id' => $employee->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $this->newLine();
        $this->info("✅ Đã xoá vĩnh viễn {$deletedCount}/{$trashedEmployees->count()} nhân viên.");

        return self::SUCCESS;
    }
}
