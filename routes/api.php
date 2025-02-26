<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\LogController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\AttendanceRecordController;
use Illuminate\Http\Request;
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
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'check.token.expiration'])->group(function () {
    // Login, Dashboard, Change Profile (Quản Lý Đăng Nhập và Trang Chủ)
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::patch('/profile', [AuthController::class, 'changeInfo']);
        Route::patch('/users/{id}/profile', [AuthController::class, 'changeProfile']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    // Employees (Quản Lý Nhân Sự)
    Route::prefix('employees')->group(function () {
        Route::get('/', [EmployeeController::class, 'index']);
        Route::post('/', [EmployeeController::class, 'store']);
        Route::get('/{id}', [EmployeeController::class, 'show']);
        Route::patch('/{id}', [EmployeeController::class, 'update']);
        Route::delete('/{id}', [EmployeeController::class, 'delete']);
        Route::get('/trash', [EmployeeController::class, 'getTrash']);
        Route::post('/{id}/restore', [EmployeeController::class, 'restore']);
    });

    // Roles (Quản Lý Chức Vụ)
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::patch('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'delete']);
    });

    // Logs (Quản Lý Logs)
    Route::prefix('logs')->group(function () {
        Route::get('/', [LogController::class, 'index']);
        Route::delete('/{id}', [LogController::class, 'delete']);
        Route::post('/delete-all', [LogController::class, 'deleteAll']);
    });
});
