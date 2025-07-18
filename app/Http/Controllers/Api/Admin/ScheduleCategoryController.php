<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\CategoryCelender;
use App\Models\Employee;
use App\Utils\SearchFilter;
use Illuminate\Http\Request;

class ScheduleCategoryController extends BaseController
{
    // GET: list category
    public function index(Request $request)
    {
        $categories = CategoryCelender::query()->select('id', 'name', 'created_at', 'updated_at');

        if (is_null($categories)) {
            return response()->json([
                'error' => [
                    'code' => 404,
                    'message' => 'No categories found!',
                ],
            ], 404);
        }

        SearchFilter::apply(
            $categories,
            $request,
            [
                'name' => 'string',
                'created_at' => 'range',
                'updated_at' => 'range',
            ],
            [
                'name',
                'created_at',
                'updated_at',
            ]
        );

        $limit = $request->limit;
        if (! is_null($limit) && $limit == 0) {
            $limit = $categories->count();
        }

        $categories = $categories->paginate($limit ?? 10);

        return response()->json($categories, 200);
    }

    // GET: detail category
    public function show($id)
    {
        $category = CategoryCelender::find($id);
        if (! $category) {
            return response()->json([
                'message' => 'This category does not exist!',
            ], 404);
        }

        return response()->json($category, 200);
    }

    // POST: Add a new category
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'Category name is required!',
            'name.string' => 'Category name must be a string!',
            'name.max' => 'Category name must not exceed 255 characters!',
        ]);

        $category = CategoryCelender::create($validated);

        return response()->json([
            'message' => 'Category added successfully!',
            'data' => $category,
        ], 201);
    }

    // PATCH: Update category
    public function update(Request $request, $id)
    {
        $category = CategoryCelender::find($id);
        if (! $category) {
            return response()->json([
                'message' => 'This category does not exist!',
            ], 404);
        }

        $category->name = trim($request->name);
        try {
            $category->save();

            return response()->json([
                'message' => 'Category updated successfully!',
                'data' => $category,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Cập nhật danh mục không thành công!']);
        }
    }

    // Xóa danh mục
    public function delete($id)
    {
        $category = CategoryCelender::find($id);
        if (! $category) {
            return response()->json([
                'error' => [
                    'code' => 404,
                    'message' => 'This category does not exist!',
                ],
            ], 404);
        }

        $employee = Employee::where('calendar_category_id', $id)->first();
        if ($employee) {
            return response()->json(
                [
                    'error' => [
                        'code' => 400,
                        'message' => 'There are employees in this category!',
                    ],
                ],
                400
            );
        }

        if ($category->delete()) {
            return response()->json([
                'message' => 'Deleted category successfully!',
            ], 200);
        } else {
            return response()->json([
                'error' => [
                    'code' => 500,
                    'message' => 'Delete category failed!',
                ],
            ], 500);
        }
    }
}
