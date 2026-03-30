<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\SidebarItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    public function index()
    {
        // Hiện trên Home: gồm 'home' và 'both'
        $homePermissions = Permission::whereIn('display_area', ['home', 'both'])
            ->orderBy('type')
            ->get();

        // Hiện trên Sidebar: gồm 'sidebar' và 'both' + load sidebarItems
        $sidebarPermissions = Permission::whereIn('display_area', ['sidebar', 'both'])
            ->with('sidebarItems') // eager load SidebarItem
            ->orderBy('type')
            ->get();

        return view('permission.index', compact(
            'homePermissions',
            'sidebarPermissions'
        ));
    }

    public function storeSidebarItem(Request $request)
    {
        try {
            // Lấy permission_id từ select hoặc hidden (khi select bị disabled)
            $permissionId = $request->input('permission_id');

            // Không validate: chỉ đảm bảo có permission_id để tránh SQL error
            if (! $permissionId) {
                toast('Thiếu permission_id', 'error', 'top-right');

                return back()->withInput();
            }

            SidebarItem::create([
                'permission_id' => $permissionId,
                'key' => $request->input('key'),
                'title' => $request->input('title'),
                'icon' => $request->input('icon'),
                'path' => $request->input('path'),
            ]);

            toast('Tạo SidebarItem thành công!', 'success', 'top-right');

            return redirect()->route('permissions.index');
        } catch (\Exception $e) {
            Log::error('Lỗi khi tạo SidebarItem', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);
            toast('Có lỗi xảy ra khi tạo SidebarItem. Vui lòng thử lại!', 'error', 'top-right');

            return back()->withInput();
        }
    }

    public function updateSidebarItem(Request $request, SidebarItem $sidebarItem)
    {
        try {
            $data = $request->all();

            // bắt buộc permission_id phải tồn tại (nếu có truyền)
            if (isset($data['permission_id']) && $data['permission_id']) {
                Permission::findOrFail($data['permission_id']);
            } else {
                $data['permission_id'] = $sidebarItem->permission_id;
            }

            // kiểm tra trùng key (loại trừ bản ghi hiện tại)
            if (isset($data['key']) && $data['key'] !== $sidebarItem->key) {
                if (SidebarItem::where('key', $data['key'])->where('id', '!=', $sidebarItem->id)->exists()) {
                    toast('Key của SidebarItem đã tồn tại!', 'error', 'top-right');

                    return back();
                }
            }
            $sidebarItem->update($data);

            toast('Cập nhật SidebarItem thành công!', 'success', 'top-right');

            return redirect()->route('permissions.index');
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật SidebarItem', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
                'sidebar_item_id' => $sidebarItem->id,
            ]);

            toast('Có lỗi xảy ra khi cập nhật SidebarItem. Vui lòng thử lại!', 'error', 'top-right');

            return back();
        }
    }

    public function destroySidebarItem(SidebarItem $sidebarItem)
    {
        try {
            $sidebarItem->delete();

            toast('Xóa SidebarItem thành công!', 'success', 'top-right');

            return redirect()->route('permissions.index');
        } catch (\Exception $e) {
            Log::error('Lỗi khi xóa SidebarItem', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'sidebar_item_id' => $sidebarItem->id,
            ]);

            toast('Có lỗi xảy ra khi xóa SidebarItem!', 'error', 'top-right');

            return back();
        }
    }

    public function store(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                Permission::create([
                    'key' => $request->input('key'),
                    'name' => $request->input('name'),
                    'type' => $request->input('type'),
                    'display_area' => $request->input('display_area'),
                ]);
            });

            toast('Tạo quyền thành công!', 'success', 'top-right');

            return redirect()->route('permissions.index');
        } catch (\Throwable $e) {
            Log::error('Lỗi khi tạo quyền', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);

            toast('Có lỗi xảy ra khi tạo quyền. Vui lòng thử lại!', 'error', 'top-right');

            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request, Permission $permission)
    {
        try {
            DB::transaction(function () use ($request, $permission) {
                $permission->update([
                    'key' => $request->input('key', $permission->key),
                    'name' => $request->input('name', $permission->name),
                    'type' => $request->input('type', $permission->type),
                    'display_area' => $request->input('display_area', $permission->display_area),
                ]);
            });

            toast('Cập nhật quyền thành công!', 'success', 'top-right');

            return redirect()->route('permissions.index');
        } catch (\Throwable $e) {
            Log::error('Lỗi khi cập nhật quyền', [
                'permission_id' => $permission->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);

            toast('Có lỗi xảy ra khi cập nhật quyền!', 'error', 'top-right');

            return redirect()->back()->withInput();
        }
    }

    public function destroy(Permission $permission)
    {
        try {
            DB::transaction(function () use ($permission) {
                // sidebar_items sẽ tự cascade delete nhờ FK onDelete('cascade')
                // KHÔNG set permission_id = null vì column là NOT NULL
                $permission->delete();
            });

            toast('Xóa quyền thành công!', 'success', 'top-right');

            return redirect()->route('permissions.index');
        } catch (\Exception $e) {
            toast('Không thể xóa quyền lúc này!', 'error', 'top-right');

            return redirect()->back();
        }
    }
}
