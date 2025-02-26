<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EmployeeController;
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

// Login, DashBoard, Change Profile (Quản Lý Đăng Nhập và Trang Chủ)
Route::prefix('admin')->middleware(['auth:sanctum', 'check.token.expiration'])->group(function () {
    // DashBoard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    // Thông tin người dùng đăng nhập
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/profile', [AuthController::class, 'profile']);
    // Nhân viên cập nhật thông tin cá nhân
    Route::patch('/change-info', [AuthController::class, 'changeInfo']);
    // Admin cập nhật thông tin nhân viên khác
    Route::patch('/change-profile/{id}', [AuthController::class, 'changeProfile']);
    // Đăng xuất
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Employee (Quản Lý Nhân Sự)
Route::prefix('employee')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [EmployeeController::class, 'index']); // Danh sách nhân viên
    Route::post('/store', [EmployeeController::class, 'store']); // Thêm mới nhân viên
    Route::get('/{id}', [EmployeeController::class, 'show']); // Chi tiết nhân viên
    Route::patch('/update/{id}', [EmployeeController::class, 'update']); // Cập nhật nhân viên
    Route::delete('/delete/{id}', [EmployeeController::class, 'delete']); // Xóa nhân viên (thùng rác)
    Route::get('/trash', [EmployeeController::class, 'getTrash']); // Danh sách nhân viên trong thùng rác
    Route::post('/restore/{id}', [EmployeeController::class, 'restore']); // Khôi phục nhân viên
});
// Role (Quản Lý Chức Vụ)
Route::prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'index']); // Lấy danh sách vai trò
    Route::post('/', [RoleController::class, 'store']); // Thêm vai trò mới
    Route::patch('/{id}', [RoleController::class, 'update']); // Cập nhật vai trò
    Route::delete('/{id}', [RoleController::class, 'delete']); // Xóa vai trò
});
