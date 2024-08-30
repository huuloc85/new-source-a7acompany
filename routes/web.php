<?php

use App\Http\Controllers\AttendanceRecordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryCelenderController;
use App\Http\Controllers\CelenderController;
use App\Http\Controllers\CheckEmployeeController;
use App\Http\Controllers\DailyProductivityHistoryController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\LoginHistoryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckPoController;
use App\Http\Controllers\StampController;
use App\Http\Controllers\ProductionPlanController;
use App\Http\Controllers\BarCodeController;
use App\Http\Controllers\HistoryPrintController;
use App\Http\Controllers\MaterialProductController;
use App\Models\LoginHistory;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'handleLogin'])->name('handleLogin');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/home', [DashBoardController::class, 'index'])->name('admin.home');
    Route::get('/profile', [AuthController::class, 'profile'])->name('admin.profile');
    Route::post('/change-profile', [AuthController::class, 'changeProfile'])->name('admin.change-profile');
    Route::post('/change-info', [AuthController::class, 'changeInfo'])->name('admin.change-info');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('admin.change-password');
    Route::post('/reset-password/{id}', [AuthController::class, 'resetPassword'])->name('admin.reset-password');

    //show lịch làm việc + bảng lương cho nhân sự
    Route::middleware(['authEmployees'])->prefix('/employee-show')->group(function () {
        Route::get('/celender', [EmployeeController::class, 'celender'])->name('admin.employee-show.celender');
        Route::get('/celender/{id}', [EmployeeController::class, 'celenderDetail'])->name('admin.employee-show.celender-detail');
        Route::get('/salary', [EmployeeController::class, 'salary'])->name('admin.employee-show.salary');
        Route::get('/salary/{id}', [EmployeeController::class, 'salaryDetail'])->name('admin.employee-show.salary-detail');
        Route::get('/update-quantity', [ProductController::class, 'updateQuantity'])->name('admin.product.update-quantity');
        Route::post('/update-quantity', [ProductController::class, 'handleUpdateQuantity'])->name('admin.product.handle-update-quantity');
        Route::get('/history-update', [ProductController::class, 'historyUpdate'])->name('admin.product.history-update');
        Route::get('/update-error', [ProductController::class, 'showUpdateError'])->name('admin.product.update-error');
        Route::post('/update-error', [ProductController::class, 'handleUpdateError'])->name('admin.product.handle-update-error');
        Route::get('/history-update-error', [ProductController::class, 'historyUpdateError'])->name('admin.product.history-update-error');
        Route::get('/history-update', [ProductController::class, 'historyUpdate'])->name('admin.product.history-update');
        //Check Employee của Nhân Viên
        Route::get('/check-employee-todo', [CheckEmployeeController::class, 'checkEmployeeTodo'])->name('admin.employee.check-employee-todo');
        Route::post('/check-employee-todo', [CheckEmployeeController::class, 'handleCheckEmployeeTodo'])->name('admin.employee.handle.check-employee-todo');
        Route::get('/check-employee-todo/history', [CheckEmployeeController::class, 'historyEmployeeCheck'])->name('admin.employee-history-check');
        Route::delete('/check-employee-todo/history/{id}', [CheckEmployeeController::class, 'deleteHistory'])->name('admin.employee.delete-employee-todo');
        Route::post('/check-employee-todo/history/{id}', [CheckEmployeeController::class, 'updateEmployee'])->name('admin.employee.update-employee-todo');
        //View của nhân viên Xem Chấm Công
        Route::get('/attendence', [AttendanceRecordController::class, 'employeeview'])->name('admin.employee.attendence_record');
    });


    //chức năng của Admin CheckEmployee
    Route::middleware(['authAdmin'])->prefix('/check-employee')->group(function () {
        Route::get('/admin-view-employee-todo', [CheckEmployeeController::class, 'index'])->name('admin.checkemployee.view-employee-todo');
        Route::delete('/admin-view-employee-todo/{id}', [CheckEmployeeController::class, 'deleteCheckEmployee'])->name('admin.checkemployee.delete');
        Route::post('/admin-check-employee-todo/edit/{id}', [CheckEmployeeController::class, 'updateEmployeeforAdmin'])->name('admin.checkemployee.update-employee-todo');
    });

    // Kế hoạch sản xuất
    Route::middleware(['authAdmin'])->prefix('/product-plan')->group(function () {
        Route::get('/index', [ProductionPlanController::class, 'index'])->name('admin.product-plan.index');
        Route::get('/add', [ProductionPlanController::class, 'addProductPlan'])->name('admin.product-plan.add');
        Route::post('/add', [ProductionPlanController::class, 'storeProductPlan'])->name('admin.product-plan.store');
        Route::post('/update', [ProductionPlanController::class, 'updateProductPlan'])->name('admin.product-plan.update');
        Route::delete('/delete/{id}', [ProductionPlanController::class, 'deleteProductPlan'])->name('admin.product-plan.delete');
        Route::get('/export', [ProductionPlanController::class, 'export'])->name('admin.product-plan.export');
        Route::get('/editConfig', [ProductionPlanController::class, 'configProductPlan'])->name('admin.product-plan.config');
        Route::post('/updateConfig', [ProductionPlanController::class, 'handleConfigProductPlan'])->name('admin.product-plan.handleConfig');
    });

    // Kế hoạch Nguyên Liệu
    Route::middleware(['authAdmin'])->prefix('/material')->group(function () {
        Route::get('/index', [MaterialProductController::class, 'index'])->name('admin.material.index');
        Route::get('/add', [MaterialProductController::class, 'add'])->name('admin.material.index.add');
        // Route::post('/add', [ProductionPlanController::class, 'storeProductPlan'])->name('admin.product-plan.store');
        // Route::post('/update', [ProductionPlanController::class, 'updateProductPlan'])->name('admin.product-plan.update');
        // Route::delete('/delete/{id}', [ProductionPlanController::class, 'deleteProductPlan'])->name('admin.product-plan.delete');
        // Route::get('/export', [ProductionPlanController::class, 'export'])->name('admin.product-plan.export');
        // Route::get('/editConfig', [ProductionPlanController::class, 'configProductPlan'])->name('admin.product-plan.config');
        // Route::post('/updateConfig', [ProductionPlanController::class, 'handleConfigProductPlan'])->name('admin.product-plan.handleConfig');
    });

    Route::middleware(['authAdmin'])->prefix('/attendence')->group(function () {
        Route::get('/index', [AttendanceRecordController::class, 'index'])->name('admin.attendence.index');
        // Route::delete('/admin-view-employee-todo/{id}', [CheckEmployeeController::class, 'deleteCheckEmployee'])->name('admin.checkemployee.delete');
        // Route::post('/admin-check-employee-todo/edit/{id}', [CheckEmployeeController::class, 'updateEmployeeforAdmin'])->name('admin.checkemployee.update-employee-todo');
    });

    //quản lý chức vụ
    Route::middleware(['authAdmin'])->prefix('/role')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('admin.role.home');
        Route::get('/add', [RoleController::class, 'add'])->name('admin.role.add');
        Route::post('/store', [RoleController::class, 'store'])->name('admin.role.store');
        Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('admin.role.edit');
        Route::post('/update/{id}', [RoleController::class, 'update'])->name('admin.role.update');
        Route::delete('/delete/{id}', [RoleController::class, 'delete'])->name('admin.role.delete');

        //log
        Route::get('/log', [LogController::class, 'index'])->name('admin.log');
        Route::delete('/log/{id}', [LogController::class, 'delete'])->name('admin.log.delete');
        Route::post('/log/delete/all', [LogController::class, 'deleteAll'])->name('admin.log.delete.all');
    });
    //history
    Route::middleware(['authAdmin'])->prefix('/history')->group(function () {
        Route::get('/', [LoginHistoryController::class, 'index'])->name('admin.history.home');
        Route::post('/delete/history', [LoginHistoryController::class, 'deleteHistory'])->name('admin.delete.history.day');
        Route::get('/daily-productivity-history', [DailyProductivityHistoryController::class, 'index'])->name('admin.daily.productivity.history');
        Route::get('/check-employees-without-logs', [DailyProductivityHistoryController::class, 'checkEmployeesWithoutLogs'])->name('admin.check.employee.withoutlogs');
        Route::get('/view-all-quantity', [LoginHistoryController::class, 'viewAllQuantity'])->name('admin.history.view.all.quantity');
    });

    //quản lý bảng lương
    Route::middleware(['authAccountant'])->prefix('/salary')->group(function () {
        Route::get('/', [SalaryController::class, 'index'])->name('admin.salary.home');
        Route::get('/import', [SalaryController::class, 'getImportSalary'])->name('admin.salary.getimport');
        Route::post('/import', [SalaryController::class, 'importSalary'])->name('admin.salary.import');
        Route::get('/detail/{id}', [SalaryController::class, 'detail'])->name('admin.salary.detail');
        Route::delete('/delete/{id}', [SalaryController::class, 'delete'])->name('admin.salary.delete');
    });

    //quản lý nhân sự
    Route::middleware(['authAdmin'])->prefix('/employee')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('admin.employee.home');
        Route::get('/add', [EmployeeController::class, 'add'])->name('admin.employee.add');
        Route::post('/store', [EmployeeController::class, 'store'])->name('admin.employee.store');
        Route::get('/edit/{id}', [EmployeeController::class, 'edit'])->name('admin.employee.edit');
        Route::post('/update/{id}', [EmployeeController::class, 'update'])->name('admin.employee.update');
        Route::delete('/delete/{id}', [EmployeeController::class, 'delete'])->name('admin.employee.delete');
        Route::get('/trash', [EmployeeController::class, 'getTrash'])->name('admin.employee.getTrash');
        Route::get('/restore/{id}', [EmployeeController::class, 'restore'])->name('admin.employee.restore');
    });

    //quản lý sản phẩm
    Route::middleware(['authAdmin'])->prefix('/product')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('admin.product.home');
        Route::get('/add', [ProductController::class, 'add'])->name('admin.product.add');
        Route::post('/store', [ProductController::class, 'store'])->name('admin.product.store');
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('admin.product.edit');
        Route::post('/update/{id}', [ProductController::class, 'update'])->name('admin.product.update');
        Route::delete('/delete/{id}', [ProductController::class, 'delete'])->name('admin.product.delete');
        Route::get('/trash', [ProductController::class, 'getTrash'])->name('admin.product.getTrash');
        Route::get('/restore/{id}', [ProductController::class, 'restore'])->name('admin.product.restore');
        Route::get('/detail/{id}', [ProductController::class, 'detail'])->name('admin.product.detail');
        Route::post('/update-detail', [ProductController::class, 'updateDetail'])->name('admin.product.update.detail');
        Route::get('/export-product', [ProductController::class, 'exportProduct'])->name('admin.export.product');
        Route::get('/update-quantity/{id}', [ProductController::class, 'updateQuantityAdmin'])->name('admin.product.update-quantity-admin');
        Route::post('/handle-update', [ProductController::class, 'handleUpdate'])->name('admin.handle-update');
        Route::delete('/delete-update-quantity/{id}', [ProductController::class, 'handleDeleteUpdateQUantity'])->name('admin.product.delete-update-quantity');
        Route::get('/update-export-200', [ProductController::class, 'updateQuantExport200'])->name('admin.product.update-export-200');
        Route::post('/handle-update-export-200', [ProductController::class, 'handleUpdateQuantExport200'])->name('admin.product.handle-update-export-200');
        Route::get('/showError', [ProductController::class, 'showProductError'])->name('admin.product.detail-error');
        // cập nhật moq
        Route::get('/update-moq', [ProductController::class, 'updateMOQ'])->name('admin.product.update-moq');
        Route::post('/handle-update-moq', [ProductController::class, 'handleUpdateMOQ'])->name('admin.product.handle-update-moq');
        //thêm sản lượng 100 200 xuất hàng hàng lỗi admin
        Route::get('/add-quantity-admin', [ProductController::class, 'viewUpdateQuantityAdmin'])->name('admin.product.add-quantity-admin');
        Route::post('/handle-add-quantity-admin', [ProductController::class, 'handleUpdateQuantityAdmin'])->name('admin.product.handle-add-quantity-admin');
        Route::get('/test', [ProductController::class, 'viewtest'])->name('admin.product.test');
        Route::get('/test/{id}/edit', [ProductController::class, 'editTest'])->name('admin.product.editTest');
        Route::post('/test/{id}', [ProductController::class, 'updateTest'])->name('admin.product.updateTest');
    });

    //barcode
    Route::middleware(['packingStamp'])->prefix('/barcode')->group(function () {
        Route::get('/', [StampController::class, 'index'])->name('admin.product.barcode');
        Route::post('/register', [StampController::class, 'barcode'])->name('admin.barcode.register');
        Route::post('/save-print', [StampController::class, 'savePrint'])->name('admin.barcode.save.print');
        Route::get('/history', [HistoryPrintController::class, 'index'])->name('admin.product.barcode.history');
    });

    //packing-stamp
    Route::middleware(['packingStamp'])->prefix('/packing')->group(function () {
        Route::get('/', [StampController::class, 'packingStamp'])->name('admin.product.packing');
        Route::post('/register', [StampController::class, 'StorePackingStamp'])->name('admin.packing.register');
        Route::post('/save-printPacking', [StampController::class, 'savePrintPacking'])->name('admin.barcode.save.print.packing');
    });

    //view check barcode for employee
    Route::prefix('/barcode/employee')->group(function () {
        Route::get('/scan', [StampController::class, 'scan'])->name('admin.barcode.scan');
        Route::post('/check', [StampController::class, 'checkBarCode'])->name('admin.barcode.check');
    });

    //check PO
    Route::middleware(['authAdmin'])->prefix('/checkpo')->group(function () {
        Route::get('/index', [CheckPoController::class, 'index'])->name('admin.checkpo.index');
        Route::post('/handle-add-po-export', [CheckPoController::class, 'handleAddPoExport'])->name('admin.checkpo.handle-add-po-export');
        Route::post('/handle-add-quantity-inventory', [CheckPoController::class, 'handleAddStockQuantityInventory'])->name('admin.checkpo.handle-add-quantity-inventory');
        Route::post('/handle-add-po-import', [CheckPoController::class, 'handleAddPoImport'])->name('admin.checkpo.handle-add-po-import');
        Route::get('/export-product', [CheckPoController::class, 'exportCheckPo'])->name('admin.checkpo.export');
        Route::get('/history-import-quantity', [CheckPoController::class, 'historyImport'])->name('admin.history-import-quantity');
        Route::get('/daily-quantities/edit/{id}', [CheckPoController::class, 'editPO'])->name('admin.checkpo.edit');
        Route::post('/daily-quantities/update', [CheckPoController::class, 'updatePO'])->name('admin.checkpo.update');
        Route::delete('/daily-quantities/delete/{id}', [CheckPoController::class, 'deletePO'])->name('admin.checkpo.delete');
    });

    //lịch làm việc
    Route::middleware(['authManager'])->prefix('/celender')->group(function () {
        Route::get('/', [CelenderController::class, 'index'])->name('admin.celender.home');
        Route::post('/add', [CelenderController::class, 'add'])->name('admin.celender.add');
        Route::post('/store/{id}', [CelenderController::class, 'store'])->name('admin.celender.store');
        Route::get('/edit/{id}', [CelenderController::class, 'edit'])->name('admin.celender.edit');
        Route::post('/update/{id}', [CelenderController::class, 'update'])->name('admin.celender.update');
        Route::get('/detail/{id}', [CelenderController::class, 'detail'])->name('admin.celender.detail');
        Route::delete('/delete/{id}', [CelenderController::class, 'delete'])->name('admin.celender.delete');
    });

    //quản lý chức vụ
    Route::middleware(['authManager'])->prefix('/category')->group(function () {
        Route::get('/', [CategoryCelenderController::class, 'index'])->name('admin.category.home');
        Route::get('/add', [CategoryCelenderController::class, 'add'])->name('admin.category.add');
        Route::post('/store', [CategoryCelenderController::class, 'store'])->name('admin.category.store');
        Route::get('/edit/{id}', [CategoryCelenderController::class, 'edit'])->name('admin.category.edit');
        Route::post('/update/{id}', [CategoryCelenderController::class, 'update'])->name('admin.category.update');
        Route::delete('/delete/{id}', [CategoryCelenderController::class, 'delete'])->name('admin.category.delete');
    });
});
