<?php

use App\Http\Controllers\Api\Admin\AttendanceHistoryController;
use App\Http\Controllers\Api\Admin\AttendanceRecordController;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\CheckStampController;
use App\Http\Controllers\Api\Admin\DailyScheduleController;
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
use App\Http\Controllers\Api\Employee\EmpAttendanceController;
use App\Http\Controllers\Api\Employee\EmpDailyActivity;
use App\Http\Controllers\Api\Employee\EmpSalaryController;
use App\Http\Controllers\Api\Employee\EmpScheduleDetailController;
use App\Http\Controllers\api\employee\EmpTodoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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
Route::post('/updateDataCC', [App\Http\Controllers\AttendanceRecordController::class, 'updateDataCC'])->name('updateDataCC');

// Attendance Records (Chấm Công)
Route::get('/acs-events/today', [AttendanceRecordController::class, 'fetchTodayEvents']);

// Đăng nhập
Route::post('/login', [AuthController::class, 'authLogin'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'authLogout'])->name('auth.logout');

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
    Route::middleware(['authAdmin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/me', [AuthController::class, 'authMe']);
        Route::get('/profile', [AuthController::class, 'authProfile']);
        Route::patch('/profile', [AuthController::class, 'authChangeInfo']);
        Route::patch('/users/{id}/profile', [AuthController::class, 'authChangeProfile']);
    });

    // Employees (Quản Lý Nhân Sự)
    Route::middleware(['authAdmin'])->prefix('employees')->group(function () {
        Route::get('/trash', [EmployeeController::class, 'getTrashEmployees']);
        Route::post('/restore/{id}', [EmployeeController::class, 'restoreEmployee']);
        Route::get('/', [EmployeeController::class, 'getEmployees']);
        Route::get('/{id}', [EmployeeController::class, 'getEmployee']);
        Route::post('/', [EmployeeController::class, 'addEmployee']);
        Route::patch('/{id}', [EmployeeController::class, 'updateEmployee']);
        Route::delete('/{id}', [EmployeeController::class, 'removeEmployee']);
    });

    // Attendance Records (Quản Lý Chấm Công)
    Route::middleware(['authAdmin'])->prefix('attendances')->group(function () {
        Route::prefix('history')->group(function () {
            Route::get('/', [AttendanceHistoryController::class, 'index']);
            Route::get('/{id}', [AttendanceHistoryController::class, 'show']);
            Route::post('/', [AttendanceHistoryController::class, 'store']);
            Route::patch('/{id}', [AttendanceHistoryController::class, 'update']);
            Route::delete('/{id}', [AttendanceHistoryController::class, 'delete']);
            // Route::post('/import', [AttendanceController::class, 'import']);
            // Route::get('/export/{id}', [AttendanceController::class, 'exportById']);
        });
        Route::get('/', [AttendanceHistoryController::class, 'detail']);
    });

    // Roles (Quản Lý Chức Vụ)
    Route::middleware(['authAdmin'])->prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'getRoles']);
        Route::get('/{id}', [RoleController::class, 'getRole']);
        Route::post('/', [RoleController::class, 'addRole']);
        Route::patch('/{id}', [RoleController::class, 'updateRole']);
        Route::delete('/{id}', [RoleController::class, 'deleteRole']);
    });

    // Logs (Quản Lý Logs)
    Route::middleware(['authAdmin'])->prefix('logs')->group(function () {
        Route::get('/', [LogController::class, 'index']);
        Route::delete('/{id}', [LogController::class, 'delete']);
        Route::post('/delete-all', [LogController::class, 'deleteAll']);
    });

    Route::middleware(['authAdmin'])->prefix('salaries')->group(function () {
        Route::get('/', [SalaryController::class, 'getSalaries']);
        Route::post('/', [SalaryController::class, 'addSalary']);
        Route::get('/{id}', [SalaryController::class, 'getSalary']);
        Route::delete('/{id}', [SalaryController::class, 'deleteSalary']);
    });

    Route::middleware(['authAdmin'])->prefix('schedule-categories')->group(function () {
        Route::get('/', [ScheduleCategoryController::class, 'index']);   // Lấy danh sách danh mục
        Route::post('/', [ScheduleCategoryController::class, 'store']);  // Thêm mới danh mục
        Route::get('/{id}', [ScheduleCategoryController::class, 'show']);  // Lấy chi tiết danh mục
        Route::patch('/{id}', [ScheduleCategoryController::class, 'update']); // Cập nhật danh mục
        Route::delete('/{id}', [ScheduleCategoryController::class, 'delete']); // Xóa danh mục
    });

    Route::middleware(['authAdmin'])->prefix('schedules')->group(function () {
        Route::prefix('detail')->group(function () {
            Route::get('/', [ScheduleDetailController::class, 'index']);
            //     Route::get('/{id}', [ScheduleDetailController::class, 'detail']);
            //     Route::post('/', [ScheduleDetailController::class, 'store']);
            //     Route::get('/{id}', [ScheduleDetailController::class, 'show']);
            //     Route::patch('/{id}', [ScheduleDetailController::class, 'update']);
            //     Route::delete('/{id}', [ScheduleDetailController::class, 'destroy']);
        });
        Route::get('/', [ScheduleController::class, 'index']);
        Route::post('/', [ScheduleController::class, 'create']);
        Route::get('/{id}', [ScheduleController::class, 'show']);
        Route::delete('/{id}', [ScheduleController::class, 'delete']);
    });

    Route::prefix('products')->group(function () {
        Route::get('/month-list', [ProductController::class, 'getMonthList']);
        Route::middleware(['authAdmin'])->get('/trash', [ProductController::class, 'getTrashProducts']);
        Route::middleware(['authAdmin'])->post('/restore/{id}', [ProductController::class, 'restoreProduct']);
        Route::get('/', [ProductController::class, 'getProducts']);
        Route::middleware(['authAdmin'])->post('/', [ProductController::class, 'addProduct']);
        Route::get('/{id}', [ProductController::class, 'getProduct']);
        Route::middleware(['authAdmin'])->patch('/{id}', [ProductController::class, 'updateProduct']);
        Route::middleware(['authAdmin'])->delete('/{id}', [ProductController::class, 'deleteProduct']);
    });

    Route::middleware(['authAdmin'])->prefix('quantities')->group(function () {
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

    Route::middleware(['authAdmin'])->prefix('stamps')->group(function () {
        Route::put('/savePrint', [StampController::class, 'savePrint'])->name('api.save-print');
        Route::get('/history', [StampController::class, 'getStampHistory'])->name('api.stamp.history');
        Route::get('/history/{id}', [StampController::class, 'getStampHistoryById'])->name('api.stamp.history.id');
        Route::post('/reject/{id}', [StampController::class, 'rejectPrint'])->name('api.stamp.reject');
    });

    Route::middleware(['authAdmin'])->prefix('daily-schedules')->group(function () {
        Route::get('/', [DailyScheduleController::class, 'index']);
        // Route::post('/', [DailyScheduleController::class, 'store']);
        Route::get('/{id}', [DailyScheduleController::class, 'show']);
        Route::patch('/{id}', [DailyScheduleController::class, 'update']);
        Route::delete('/{id}', [DailyScheduleController::class, 'destroy']);
    });

    Route::middleware(['authAdmin'])->prefix('history')->group(function () {
        Route::get('/', [HistoryController::class, 'index'])->name('api.history.index');
        Route::delete('/{id}', [HistoryController::class, 'destroy'])->name('api.history.destroy');
        Route::get('/view-log-all-quantity', [HistoryController::class, 'viewLogAllQuantity'])->name('api.history.view-log-all-quantity');
    });

    Route::middleware(['authAdmin'])->get('/check-stamp', [CheckStampController::class, 'index'])->name('api.check-stamp');

    // EMPLOYEE ROUTES
    Route::middleware(['authEmployees'])->prefix('employee')->group(function () {
        Route::prefix('schedules')->group(function () {
            Route::get('/', [ScheduleController::class, 'index']);
            Route::get('/{id}', [EmpScheduleDetailController::class, 'show']);
        });

        Route::prefix('salaries')->group(function () {
            Route::get('/', [EmpSalaryController::class, 'empSalaries']);
            Route::get('/{id}', [EmpSalaryController::class, 'empSalary']);
        });

        Route::prefix('attendances')->group(function () {
            Route::get('/history', [EmpAttendanceController::class, 'history']);
            Route::get('/', [EmpAttendanceController::class, 'index']);
            // Route::get('/{id}', [EmpAttendance::class, 'show']);
            // Route::patch('/{id}', [EmpAttendance::class, 'update']);
        });

        Route::prefix('daily-activities')->group(function () {
            Route::get('/', [EmpDailyActivity::class, 'index']);
            Route::get('/{id}', [EmpDailyActivity::class, 'show']);
            Route::patch('/{id}', [EmpDailyActivity::class, 'update']);
            Route::delete('/{id}', [EmpDailyActivity::class, 'destroy']);
        });

        Route::prefix('todos')->group(function () {
            Route::prefix('quantity')->group(function () {
                Route::put('/', [EmpTodoController::class, 'handleUpdateQuantity']);
                Route::put('/error', [EmpTodoController::class, 'handleUpdateError']);
            });
            Route::get('/', [EmpTodoController::class, 'index']);
            Route::post('/', [EmpTodoController::class, 'store']);
            Route::get('/history', [EmpTodoController::class, 'history']);

        });
    });
});
