<?php

namespace App\Http\Controllers\Api;

use App\Models\Log;

class LogController extends BaseController
{
    // Lấy danh sách log
    public function index()
    {
        $logs = Log::orderBy('created_at', 'DESC')->get();

        return response()->json([
            'success' => true,
            'data' => $logs,
            'total' => count($logs),
        ]);
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
            return response()->json([
                'success' => false,
                'message' => 'Xóa log không thành công!',
            ]);
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
            return response()->json([
                'success' => false,
                'message' => 'Xóa tất cả log không thành công!',
            ]);
        }
    }
}
