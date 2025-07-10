<?php

use App\Http\Controllers\Api\Admin\AttendanceHistoryController;
use App\Http\Controllers\Api\Admin\AttendanceRecordController;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\CheckStampController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\EmployeeController;
use App\Http\Controllers\Api\Admin\HistoryController;
use App\Http\Controllers\Api\Admin\LogController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\SalaryController;
use App\Http\Controllers\Api\Admin\ScheduleCategoryController;
use App\Http\Controllers\Api\Admin\ScheduleController;
use App\Http\Controllers\Api\Admin\ScheduleDetailController;
use App\Http\Controllers\Api\Admin\StampController;
use App\Http\Controllers\Api\Admin\TotalQuantityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/updateDataCC', [AttendanceRecordController::class, 'updateDataCC'])->name('updateDataCC');

// Đăng nhập
Route::post('/login', [AuthController::class, 'authLogin']);
Route::post('/logout', [AuthController::class, 'authLogout']);

// clear cache
app()->environment('local') && Route::get('/clear-cache', function () {
    if (connection_aborted()) {
        Log::info('Request aborted early.');
    }

    Artisan::call('cache:clear');

    return response()->json(['message' => 'Cache cleared successfully']);
});

Route::middleware(['auth:sanctum', 'check.token.expiration'])->group(function () {
    if (connection_aborted()) {
        Log::info('Request aborted early.');

        return;
    }

    Route::post('/auth/check', [AuthController::class, 'authCheck']);

    // Login, Dashboard, Change Profile (Quản Lý Đăng Nhập và Trang Chủ)
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/me', [AuthController::class, 'authMe']);
        Route::get('/profile', [AuthController::class, 'authProfile']);
        Route::patch('/profile', [AuthController::class, 'authChangeInfo']);
        Route::patch('/users/{id}/profile', [AuthController::class, 'authChangeProfile']);
    });

    // Employees (Quản Lý Nhân Sự)
    Route::prefix('employees')->group(function () {
        Route::get('/trash', [EmployeeController::class, 'getTrashEmployees']);
        Route::post('/restore/{id}', [EmployeeController::class, 'restoreEmployee']);
        Route::get('/', [EmployeeController::class, 'getEmployees']);
        Route::get('/{id}', [EmployeeController::class, 'getEmployee']);
        Route::post('/', [EmployeeController::class, 'addEmployee']);
        Route::patch('/{id}', [EmployeeController::class, 'updateEmployee']);
        Route::delete('/{id}', [EmployeeController::class, 'removeEmployee']);
    });

    // Attendance Records (Quản Lý Chấm Công)
    Route::prefix('attendances')->group(function () {
        Route::prefix('history')->group(function () {
            Route::get('/', [AttendanceHistoryController::class, 'index']);
            Route::get('/{id}', [AttendanceHistoryController::class, 'show']);
            Route::post('/', [AttendanceHistoryController::class, 'store']);
            Route::patch('/{id}', [AttendanceHistoryController::class, 'update']);
            Route::delete('/{id}', [AttendanceHistoryController::class, 'delete']);
            // Route::post('/import', [AttendanceController::class, 'import']);
            // Route::get('/export', [AttendanceController::class, 'export']);
            // Route::get('/export/{id}', [AttendanceController::class, 'exportById']);
        });
        Route::get('/details', [AttendanceRecordController::class, 'records']);
    });

    // Roles (Quản Lý Chức Vụ)
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'getRoles']);
        Route::get('/{id}', [RoleController::class, 'getRole']);
        Route::post('/', [RoleController::class, 'addRole']);
        Route::patch('/{id}', [RoleController::class, 'updateRole']);
        Route::delete('/{id}', [RoleController::class, 'deleteRole']);
    });

    // Logs (Quản Lý Logs)
    Route::prefix('logs')->group(function () {
        Route::get('/', [LogController::class, 'index']);
        Route::delete('/{id}', [LogController::class, 'delete']);
        Route::post('/delete-all', [LogController::class, 'deleteAll']);
    });

    Route::prefix('salaries')->group(function () {
        Route::get('/', [SalaryController::class, 'getSalaries']);
        Route::post('/', [SalaryController::class, 'addSalary']);
        Route::get('/{id}', [SalaryController::class, 'getSalary']);
        Route::delete('/{id}', [SalaryController::class, 'deleteSalary']);
    });

    Route::prefix('schedule-categories')->group(function () {
        Route::get('/', [ScheduleCategoryController::class, 'index']);   // Lấy danh sách danh mục
        Route::post('/', [ScheduleCategoryController::class, 'store']);  // Thêm mới danh mục
        Route::get('/{id}', [ScheduleCategoryController::class, 'show']);  // Lấy chi tiết danh mục
        Route::patch('/{id}', [ScheduleCategoryController::class, 'update']); // Cập nhật danh mục
        Route::delete('/{id}', [ScheduleCategoryController::class, 'delete']); // Xóa danh mục
    });

    Route::prefix('schedules')->group(function () {
        // Route::prefix('details')->group(function () {
        //     Route::get('/', [ScheduleDetailController::class, 'index']);
        //     Route::get('/{id}', [ScheduleDetailController::class, 'detail']);
        //     Route::post('/', [ScheduleDetailController::class, 'store']);
        //     Route::get('/{id}', [ScheduleDetailController::class, 'show']);
        //     Route::patch('/{id}', [ScheduleDetailController::class, 'update']);
        //     Route::delete('/{id}', [ScheduleDetailController::class, 'destroy']);
        // });
        Route::get('/', [ScheduleController::class, 'index']);
        Route::post('/', [ScheduleController::class, 'create']);
        Route::get('/{id}', [ScheduleController::class, 'detail']);
        Route::delete('/{id}', [ScheduleController::class, 'delete']);
    });

    Route::prefix('products')->group(function () {
        Route::get('/month-list', [ProductController::class, 'getMonthList']);
        Route::get('/trash', [ProductController::class, 'getTrashProducts']);
        Route::post('/restore/{id}', [ProductController::class, 'restoreProduct']);
        Route::get('/', [ProductController::class, 'getProducts']);
        Route::post('/', [ProductController::class, 'addProduct']);
        Route::get('/{id}', [ProductController::class, 'getProduct']);
        Route::patch('/{id}', [ProductController::class, 'updateProduct']);
        Route::delete('/{id}', [ProductController::class, 'deleteProduct']);
    });

    Route::prefix('quantities')->group(function () {
        Route::post('/addList', [TotalQuantityController::class, 'addProductsQuantity']);
        Route::prefix('monthly')->group(function () {
            Route::patch('/updateList', [TotalQuantityController::class, 'updateMonthQuantities']);
            Route::get('/', [TotalQuantityController::class, 'getMonthly']);
            Route::patch('/{id}', [TotalQuantityController::class, 'updateMonthQuantity']);
        });
        // TODO: Implement more if needed
        // Route::post('/', [TotalQuantityController::class, 'store']);
        // Route::get('/{id}', [TotalQuantityController::class, 'show']);
        // Route::delete('/{id}', [TotalQuantityController::class, 'delete']);
    });

    Route::prefix('stamps')->group(function () {
        Route::post('/savePrint', [StampController::class, 'savePrint'])->name('api.save-print');
    });

    Route::middleware(['auth:sanctum', 'authEmployees'])->group(function () {
        Route::get('/calendar', [EmployeeController::class, 'calendar'])->name('api.employee.calendar');
        Route::get('/calendar/{id}', [EmployeeController::class, 'calendarDetail'])->name('api.employee.calendar-detail');

        Route::get('/salary', [EmployeeController::class, 'salary'])->name('api.employee.salary');
        Route::get('/salary/{id}', [EmployeeController::class, 'salaryDetail'])->name('api.employee.salary-detail');

        Route::get('/update-quantity', [ProductController::class, 'updateQuantity'])->name('api.product.update-quantity');
        Route::post('/update-quantity', [ProductController::class, 'handleUpdateQuantity'])->name('api.product.handle-update-quantity');

        Route::get('/history-update', [ProductController::class, 'historyUpdate'])->name('api.product.history-update');

        Route::get('/update-error', [ProductController::class, 'showUpdateError'])->name('api.product.update-error');
        Route::post('/update-error', [ProductController::class, 'handleUpdateError'])->name('api.product.handle-update-error');
        Route::get('/history-update-error', [ProductController::class, 'historyUpdateError'])->name('api.product.history-update-error');

        Route::get('/check-employee-todo', [CheckEmployeeController::class, 'checkEmployeeTodo'])->name('api.employee.check-employee-todo');
        Route::post('/check-employee-todo', [CheckEmployeeController::class, 'handleCheckEmployeeTodo'])->name('api.employee.handle.check-employee-todo');
        Route::get('/check-employee-todo/history', [CheckEmployeeController::class, 'historyEmployeeCheck'])->name('api.employee-history-check');
        Route::delete('/check-employee-todo/history/{id}', [CheckEmployeeController::class, 'deleteHistory'])->name('api.employee.delete-employee-todo');
        Route::post('/check-employee-todo/history/{id}', [CheckEmployeeController::class, 'updateEmployee'])->name('api.employee.update-employee-todo');

        Route::get('/attendance', [AttendanceRecordController::class, 'employeeViewRecords'])->name('api.employee.attendance');
        Route::get('/attendance-calculate', [AttendanceRecordController::class, 'employeeViewCaculateRecords'])->name('api.employee.attendance-calculate');

        Route::get('/send-stamp', [SendStampController::class, 'index'])->name('api.send-stamp');
        Route::post('/send-stamp', [SendStampController::class, 'handleAdd'])->name('api.handleAdd-send-stamp');
        Route::get('/send-stamp/status', [SendStampController::class, 'checkStampEmployee'])->name('api.checkstamp-employee');
    });

    Route::prefix('history')->group(function () {
        Route::get('/', [HistoryController::class, 'index'])->name('api.history.index');
        Route::delete('/{id}', [HistoryController::class, 'destroy'])->name('api.history.destroy');
        Route::get('/view-log-all-quantity', [HistoryController::class, 'viewLogAllQuantity'])->name('api.history.view-log-all-quantity');
    });

    Route::get('/check-stamp', [CheckStampController::class, 'index'])->name('api.check-stamp');
});
