<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
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

// Các route yêu cầu xác thực bằng Sanctum
Route::middleware('auth:sanctum')->group(function () {
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
