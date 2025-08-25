<?php

namespace App\Http\Controllers\Api;

use App\Helpers\HandleError;
use App\Http\Controllers\Api\Admin\BaseController;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class NotificationController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $notifications = QueryBuilder::for(Notification::class)
                ->allowedIncludes('employee')
                ->allowedFilters([
                    'message',
                    'created_at',
                    'employee_id',
                    'employee.name',
                    AllowedFilter::callback('is_show', function ($query, $value) {
                        $query->where('is_show', filter_var($value, FILTER_VALIDATE_BOOLEAN));
                    }),
                ])
                ->paginate($request->input('limit', 10));

            return response()->json($notifications, 200);

        } catch (\Throwable $th) {
            return HandleError::handle($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $employeeId = auth()->id();

            $validated = $request->validate([
                'message' => 'required|string|max:255',
                'is_show' => 'nullable|boolean',
            ]);

            $notification = Notification::create(array_merge($validated, ['employee_id' => $employeeId]));
            DB::commit();

            return response()->json($notification, 201);
        } catch (\Throwable $th) {
            DB::rollBack();

            return HandleError::handle($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $notification = Notification::findOrFail($id);

            return response()->json($notification, 200);
        } catch (\Throwable $th) {
            return HandleError::handle($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $notification = Notification::findOrFail($id);
            $validated = $request->validate([
                'message' => 'sometimes|string|max:255',
                'is_show' => 'sometimes|boolean',
            ]);
            $notification->update($validated);

            return response()->json($notification, 200);
        } catch (\Throwable $th) {
            return HandleError::handle($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $notification = Notification::findOrFail($id);
            $notification->delete();

            return response()->json($notification, 200);
        } catch (\Throwable $th) {
            return HandleError::handle($th);
        }
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        try {
            $notification = Notification::onlyTrashed()->findOrFail($id);
            $notification->restore();

            return response()->json($notification, 200);
        } catch (\Throwable $th) {
            return HandleError::handle($th);
        }
    }

    /**
     * Display a list of the removed resources.
     */
    public function trashed(Request $request)
    {
        try {
            $notifications = QueryBuilder::for(Notification::onlyTrashed())
                ->allowedIncludes('employee')
                ->allowedFilters([
                    'message',
                    'created_at',
                    'employee_id',
                    'employee.name',
                    AllowedFilter::callback('is_show', function ($query, $value) {
                        $query->where('is_show', filter_var($value, FILTER_VALIDATE_BOOLEAN));
                    }),
                ])
                ->paginate($request->input('limit', 10));

            return response()->json($notifications, 200);
        } catch (\Throwable $th) {
            return HandleError::handle($th);
        }
    }

    /**
     * Force delete the specified resource from storage.
     */
    public function forceDelete($id)
    {
        try {
            $notification = Notification::onlyTrashed()->findOrFail($id);
            $notification->forceDelete();

            return response()->json($notification, 200);
        } catch (\Throwable $th) {
            return HandleError::handle($th);
        }
    }
}
