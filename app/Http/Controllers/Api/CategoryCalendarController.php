<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryCelender;
use App\Models\Employee;
use Illuminate\Http\Request;

class CategoryCalendarController extends Controller
{
    // Lấy danh sách danh mục
    public function index(Request $request)
    {
        $categories = CategoryCelender::Search();
        $total = count($categories->get());
        $categories = $categories->orderBy('id', 'DESC')->paginate(10);

        return response()->json([
            'success' => true,
            'total' => $total,
            'data' => $categories,
        ]);
    }

    // Lấy chi tiết danh mục
    public function show($id)
    {
        $category = CategoryCelender::find($id);
        if (! $category) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy danh mục!'], 404);
        }

        return response()->json(['success' => true, 'data' => $category]);
    }

    // Thêm mới danh mục
    public function store(Request $request)
    {
        $category = new CategoryCelender;
        $category->name = trim($request->name);

        try {
            $category->save();

            return response()->json(['success' => true, 'message' => 'Thêm danh mục mới thành công!', 'data' => $category]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Thêm danh mục không thành công!']);
        }
    }

    // Cập nhật danh mục
    public function update(Request $request, $id)
    {
        $category = CategoryCelender::find($id);
        if (! $category) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy danh mục!'], 404);
        }

        $category->name = trim($request->name);
        try {
            $category->save();

            return response()->json(['success' => true, 'message' => 'Cập nhật danh mục thành công!', 'data' => $category]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Cập nhật danh mục không thành công!']);
        }
    }

    // Xóa danh mục
    public function delete($id)
    {
        $category = CategoryCelender::find($id);
        if (! $category) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy danh mục!'], 404);
        }

        $employee = Employee::where('category_celender_id', $id)->exists();
        if ($employee) {
            return response()->json(['success' => false, 'message' => 'Có nhân viên thuộc danh mục này!']);
        }

        try {
            $category->delete();

            return response()->json(['success' => true, 'message' => 'Xóa danh mục thành công!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Xóa danh mục không thành công!']);
        }
    }
}
