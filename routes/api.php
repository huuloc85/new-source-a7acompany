<?php

use App\Http\Controllers\Api\AuthController;
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

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']); // Lấy thông tin profile
    Route::post('/change-profile/{id}', [AuthController::class, 'changeProfile']);
    Route::post('/change-info', [AuthController::class, 'changeInfo']); // Nhân viên tự cập nhật
});
