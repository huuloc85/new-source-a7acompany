<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\CategoryCelender;
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Http\Request;

class CategoryCelenderController extends Controller
{
    // view list
    public function index(Request $request) {
        $categories = CategoryCelender::Search();
        $total = count($categories->get());
        $categories = $categories->orderBy('id', 'DESC')->paginate(Role::paginate);
        return view('category.index', compact('categories', 'total'));
    }

    //view add
    public function add () {
        return view('category.add');
    }

    //store new role
    public function store(CategoryStoreRequest $request) {
        $category = new CategoryCelender();
        $category->name = trim($request->name);

        try {
            $category->save();
            toast('Thêm danh mục mới thành công!','success','top-right');
            return redirect()->route('admin.category.home');
        } catch (\Exception $th) {
            toast('Thêm danh mục mới không thành công!','error','top-right');
            return redirect()->back();
        }
    }

    //view edit
    public function edit($id) {
        $category = CategoryCelender::find($id);
        return view('category.edit', compact('category'));
    }

    //update role
    public function update(CategoryUpdateRequest $request, $id) {
        $category = CategoryCelender::find($id);
        $category->name = trim($request->name);
        try {
            $category->save();
            toast('Cập nhật danh mục thành công!','success','top-right');
            return redirect()->route('admin.category.home');
        } catch (\Exception $e) {
            dd($e->getMessage());
            toast('Cập nhật danh mục không thành công!','error','top-right');
            return redirect()->back();
        }
    }

    //delete role
    public function delete($id) {
        $category = CategoryCelender::findOrFail($id);
        try {
            $employee = Employee::where('category_celender_id', $id)->first();
            if ($employee) {
                toast('Có nhân viên thuộc danh mục này!','error','top-right');
                return redirect()->route('admin.category.home');
            }
            $category->delete();
            toast('Xóa danh mục thành công!','success','top-right');
            return redirect()->route('admin.category.home');
        } catch (\Exception $e) {
            toast('Xóa danh mục không thành công!','error','top-right');
            return redirect()->route('admin.category.home');
        }
    }
}
