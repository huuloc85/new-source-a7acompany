<?php

namespace App\Http\Controllers\Api\Admin;

use App\Events\FeedbackReplied;
use App\Models\Feedback;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FeedbackController extends BaseController
{
    /**
     * GET /api/feedbacks
     * Admin xem tất cả góp ý từ nhân viên
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Feedback::with(['employee:id,name,phone', 'repliedByEmployee:id,name']);

            if ($status = $request->input('status')) {
                $query->where('status', $status);
            }

            if ($type = $request->input('type')) {
                $query->where('type', $type);
            }

            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('subject', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            }

            $feedbacks = $query->orderBy('created_at', 'desc')->paginate(20);

            return response()->json($feedbacks);
        } catch (\Throwable $e) {
            Log::error('Feedback index error', ['message' => $e->getMessage()]);

            return response()->json(['message' => 'Có lỗi xảy ra.'], 500);
        }
    }

    /**
     * GET /api/feedbacks/{id}
     * Admin xem chi tiết góp ý
     */
    public function show(string $id): JsonResponse
    {
        try {
            $feedback = Feedback::with(['employee:id,name,phone', 'repliedByEmployee:id,name'])
                ->findOrFail($id);

            return response()->json($feedback);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Illuminate\Support\Facades\Log::error(basename(__FILE__) . ' - ' . __FUNCTION__ . ' - Error: ' . $e->getMessage());
            return response()->json(['message' => 'Góp ý không tồn tại.'], 404);
        } catch (\Throwable $e) {
            Log::error('Feedback show error', ['message' => $e->getMessage()]);

            return response()->json(['message' => 'Có lỗi xảy ra.'], 500);
        }
    }

    /**
     * POST /api/feedbacks/{id}/reply
     * Admin phản hồi góp ý
     */
    public function reply(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'admin_reply' => 'required|string|max:5000',
            'status' => 'sometimes|in:reviewed,resolved,rejected',
        ]);

        try {
            $feedback = Feedback::findOrFail($id);

            $feedback->update([
                'admin_reply' => $request->input('admin_reply'),
                'status' => $request->input('status', 'reviewed'),
                'replied_by' => auth()->id(),
                'replied_at' => now(),
            ]);

            // Refresh để lấy đúng giá trị replied_at đã cast
            $feedback->refresh();
            $feedback->load(['employee:id,name,phone', 'repliedByEmployee:id,name']);

            // Broadcast realtime cho employee
            event(new FeedbackReplied($feedback));

            return response()->json([
                'message' => 'Đã phản hồi góp ý!',
                'feedback' => $feedback,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Illuminate\Support\Facades\Log::error(basename(__FILE__) . ' - ' . __FUNCTION__ . ' - Error: ' . $e->getMessage());
            return response()->json(['message' => 'Góp ý không tồn tại.'], 404);
        } catch (\Throwable $e) {
            Log::error('Feedback reply error', ['message' => $e->getMessage()]);

            return response()->json(['message' => 'Có lỗi xảy ra.'], 500);
        }
    }

    /**
     * DELETE /api/feedbacks/{id}
     * Admin xóa góp ý
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $feedback = Feedback::findOrFail($id);

            // Xóa file ảnh nếu có
            if ($feedback->image) {
                Storage::disk('public')->delete($feedback->image);
            }

            $feedback->delete();

            return response()->json(['message' => 'Đã xóa góp ý.']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Illuminate\Support\Facades\Log::error(basename(__FILE__) . ' - ' . __FUNCTION__ . ' - Error: ' . $e->getMessage());
            return response()->json(['message' => 'Góp ý không tồn tại.'], 404);
        } catch (\Throwable $e) {
            Log::error('Feedback destroy error', ['message' => $e->getMessage()]);

            return response()->json(['message' => 'Có lỗi xảy ra.'], 500);
        }
    }
}
