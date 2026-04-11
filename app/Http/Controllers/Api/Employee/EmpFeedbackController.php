<?php

namespace App\Http\Controllers\Api\Employee;

use App\Events\FeedbackCreated;
use App\Models\Feedback;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EmpFeedbackController
{
    /**
     * GET /api/employee/feedbacks
     * Employee xem danh sách góp ý của chính mình
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Feedback::where('employee_id', auth()->id())
                ->with('repliedByEmployee:id,name');

            if ($status = $request->input('status')) {
                $query->where('status', $status);
            }

            if ($type = $request->input('type')) {
                $query->where('type', $type);
            }

            $feedbacks = $query->orderBy('created_at', 'desc')->paginate(20);

            return response()->json($feedbacks);
        } catch (\Throwable $e) {
            Log::error('EmpFeedback index error', ['message' => $e->getMessage()]);

            return response()->json(['message' => 'Có lỗi xảy ra.'], 500);
        }
    }

    /**
     * POST /api/employee/feedbacks
     * Employee gửi góp ý mới (hỗ trợ upload hình ảnh)
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:suggestion,bug,complaint,other',
            'subject' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Tối đa 5MB
        ]);

        try {
            $imagePath = null;

            // Xử lý upload hình ảnh
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = 'feedback_'.auth()->id().'_'.time().'.'.$image->getClientOriginalExtension();
                $imagePath = $image->storeAs('uploads/feedbacks', $fileName, 'public');
            }

            $feedback = Feedback::create([
                'employee_id' => auth()->id(),
                'type' => $request->input('type'),
                'subject' => $request->input('subject'),
                'content' => $request->input('content'),
                'image' => $imagePath,
            ]);

            // Broadcast realtime cho admin
            event(new FeedbackCreated($feedback));

            return response()->json([
                'message' => 'Đã gửi góp ý thành công!',
                'feedback' => $feedback,
            ], 201);
        } catch (\Throwable $e) {
            Log::error('EmpFeedback store error', ['message' => $e->getMessage()]);

            return response()->json(['message' => 'Có lỗi xảy ra khi gửi góp ý.'], 500);
        }
    }

    /**
     * GET /api/employee/feedbacks/{id}
     * Employee xem chi tiết góp ý của mình
     */
    public function show(string $id): JsonResponse
    {
        try {
            $feedback = Feedback::where('employee_id', auth()->id())
                ->with('repliedByEmployee:id,name')
                ->findOrFail($id);

            return response()->json($feedback);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Illuminate\Support\Facades\Log::error(basename(__FILE__) . ' - ' . __FUNCTION__ . ' - Error: ' . $e->getMessage());
            return response()->json(['message' => 'Góp ý không tồn tại.'], 404);
        } catch (\Throwable $e) {
            Log::error('EmpFeedback show error', ['message' => $e->getMessage()]);

            return response()->json(['message' => 'Có lỗi xảy ra.'], 500);
        }
    }

    /**
     * DELETE /api/employee/feedbacks/{id}
     * Employee chỉ xóa được góp ý pending của mình
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $feedback = Feedback::where('employee_id', auth()->id())
                ->findOrFail($id);

            if ($feedback->status !== 'pending') {
                return response()->json(['message' => 'Chỉ xóa được góp ý đang chờ duyệt.'], 403);
            }

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
            Log::error('EmpFeedback destroy error', ['message' => $e->getMessage()]);

            return response()->json(['message' => 'Có lỗi xảy ra.'], 500);
        }
    }
}
