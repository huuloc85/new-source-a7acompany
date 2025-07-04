<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\HandleError;
use App\Models\Role;
use DB;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class RoleController extends BaseController
{
    // API
    public function getRoles(Request $request)
    {
        try {
            $roles = QueryBuilder::for(Role::class)
                ->select('id', 'role_name', 'created_at', 'updated_at')
                ->allowedFilters('role_name', 'created_at', 'updated_at')
                ->defaultSort('-id')
                ->allowedSorts(['id', 'role_name', 'created_at', 'updated_at'])
                ->whereNotIn('id', [15, 16, 17]);

            $limit = $request->limit;
            if (! is_null($limit) && $limit == 0) {
                $limit = $roles->count();
            }
            $roles = $roles->paginate($limit ?? 10);

            return response()->json($roles);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    public function getRole($id)
    {
        try {
            $role = Role::query()
                ->select(
                    'id',
                    'role_name',
                    'created_at',
                    'updated_at'
                )
                ->whereNotIn('id', [15, 16, 17])
                ->findOrFail($id);

            return response()->json($role);
        } catch (\Throwable $e) {
            return HandleError::handle($e);
        }
    }

    public function addRole(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'role_name' => 'required|string|unique:roles,role_name',
            ]);

            $role = Role::create([
                'role_name' => $validated['role_name'],
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Role created successfully!',
                'data' => $role,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return HandleError::handle($e);
        }
    }

    public function updateRole(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $role = Role::query()
                ->select(
                    'id',
                    'role_name',
                    'created_at',
                    'updated_at'
                )
                ->where('id', $id)
                ->whereNotIn('id', [15, 16, 17])
                ->firstOrFail();

            $validated = $request->validate([
                'role_name' => 'sometimes|string|unique:roles,role_name,'.$id,
            ]);

            $role->update($validated);

            DB::commit();

            return response()->json([
                'message' => 'Role updated successfully!',
                'data' => $role,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return HandleError::handle($e);
        }
    }

    public function deleteRole($id)
    {
        DB::beginTransaction();
        try {
            $role = Role::query()
                ->select('id', 'role_name', 'created_at', 'updated_at', 'deleted_at')
                ->where('id', $id)
                ->whereNotIn('id', [15, 16, 17])
                ->firstOrFail();

            $role->delete();

            DB::commit();

            return response()->json([
                'message' => 'Role deleted successfully!',
                'data' => $role,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return HandleError::handle($e);
        }
    }
}
