<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();                                       //ID sản phẩm
            $table->string('code')->nullable();                 //Mã sản phẩm
            $table->string('name')->nullable();                 //Tên sản phẩm
            $table->integer('quantity')->nullable();            //Sản lượng
            // $table->integer('quantityCaTon')->nullable();       //Số lượng thùng caton
            $table->string('moldSize')->nullable();             //Kích thước khuôn
            $table->double('CAV', 19, 2)->nullable();           //Số cái/shot
            $table->double('cycle', 19, 2)->nullable();         //Chu kì s/shot
            $table->integer('FAPV')->nullable();                //Công ty A
            $table->integer('FASV')->nullable();                //Công ty B
            $table->integer('FAVV')->nullable();                //Công ty C
            // $table->double('planTime', 19, 2)->nullable();      //Dự định thời gian hoạt động thiết bị(ngày/tháng)
            // $table->double('realTime', 19, 2)->nullable();      //Thực tế thời gian hoạt động thiết bị(ngày/tháng)
            $table->string('binCode')->nullable();              //Mã thùng
            $table->integer('quanEntityBin')->nullable();       //Số lượng con/thùng
            // $table->integer('stockQuan')->nullable();           //Số lượng tồn đầu kì
            // $table->integer('stockQuan200')->nullable();        //Số lượng tồn đầu hàng 200%
            // $table->integer('prorealityQuan')->nullable();      //Tổng số lượng sản xuất thực tế (nhân viên nhập vào hằng ngày)(status1)
            // $table->integer('exportedQuan')->nullable();        //Tổng số lượng đã xuất(nhân viên nhập số lượng xuất hằng ngày đã kiểm 200%->xuất)->tổng(status3)
            // $table->integer('importedQuan')->nullable();        //Tổng số lượng đã nhập (200%)
            // $table->integer('produceQuan')->nullable();         //Tổng số lượng sản xuất (Tổng số lượng sản xuất thực tế(prorealityQuan) + Số lượng tồn đầu kì(stockQuan))
            // $table->integer('checkedQuan')->nullable();         //Tổng số lượng đã kiểm (200%) (số lượng nhập hàng hằng ngày + Tồn đầu hàng 200%)
            // $table->integer('stockTotal')->nullable();          //Tổng số lượng tồn kho (Số lượng sản xuất(produceQuan) - số lượng đã xuất(exportedQuan))
            // $table->integer('uncheckedQuan')->nullable();       //Tổng số lượng chưa kiểm (200%)(Số lượng tồn kho (stockTotal) - Tổng số lượng đã kiểm(checkedQuan))
            // $table->integer('stockDayTotal')->nullable();       //Tổng số ngày tồn kho(Tổng số lượng tồn(stockTotal)\(Sản lượng(quantity)\24))
            // $table->string('monthUpdate')->nullable();          //Thời gian cập nhật số lượng tồn đầu hàng 200% lưu theo dạng 03/2024
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
