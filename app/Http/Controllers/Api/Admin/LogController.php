<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Log;
use Illuminate\Support\Facades\File;

class LogController extends BaseController
{
    // Lấy danh sách log
    public function index()
    {
        try {
            $logs = Log::orderBy('created_at', 'DESC')->get();

            return response()->json([
                'success' => true,
                'data' => $logs,
                'total' => count($logs),
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error(basename(__FILE__) . ' - ' . __FUNCTION__ . ' - Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi khi lấy danh sách log',
            ], 500);
        }
    }

    // Xóa một log theo ID
    public function delete($id)
    {
        $log = Log::find($id);

        if (! $log) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy log!',
            ]);
        }

        try {
            $log->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa log thành công!',
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error(basename(__FILE__) . ' - ' . __FUNCTION__ . ' - Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Xóa log không thành công!',
            ], 500);
        }
    }

    // Xóa tất cả log
    public function deleteAll()
    {
        try {
            Log::truncate();

            return response()->json([
                'success' => true,
                'message' => 'Xóa tất cả log thành công!',
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error(basename(__FILE__) . ' - ' . __FUNCTION__ . ' - Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Xóa tất cả log không thành công!',
            ], 500);
        }
    }

    // Lấy nội dung System Logs (từ file laravel.log)
    public function getSystemLogs()
    {
        try {
            $logPath = storage_path('logs/laravel.log');
            
            if (!File::exists($logPath)) {
                return response()->json([
                    'success' => true,
                    'data' => '',
                    'message' => 'Log file not found.',
                ]);
            }

            // Read the last 1000 lines securely
            $content = '';
            if (filesize($logPath) > 0) {
                $file = new \SplFileObject($logPath, 'r');
                $file->seek(PHP_INT_MAX);
                $totalLines = $file->key();
                $start = max(0, $totalLines - 1000);

                $file->seek($start);
                while (!$file->eof()) {
                    $content .= $file->current();
                    $file->next();
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => $content,
            ]);
        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\Log::error(basename(__FILE__) . ' - ' . __FUNCTION__ . ' - Error: ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tải log hệ thống: ' . $th->getMessage()
            ], 500);
        }
    }

    // Xóa nội dung System Logs (từ file laravel.log)
    public function clearSystemLogs()
    {
        try {
            $logPath = storage_path('logs/laravel.log');
            
            if (File::exists($logPath)) {
                File::put($logPath, '');
            }

            return response()->json([
                'success' => true,
                'message' => 'Cleared system logs successfully!',
            ]);
        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\Log::error(basename(__FILE__) . ' - ' . __FUNCTION__ . ' - Error: ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa log hệ thống: ' . $th->getMessage()
            ], 500);
        }
    }
}
