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
        Schema::create('dailyquantities', function (Blueprint $table) {
            $table->id();                                                                       //ID của số liệu hàng ngày
            $table->foreignId('product_id')->references('id')->on('products');                  //ID của sản phẩm
            $table->integer('quantity')->nullable();                                            //Số lượng cần nhập
            $table->integer('status')->nullable();                                              //Trạng thái nhập (*)
            $table->date('date')->nullable();                                                   //Ngày nhập số lượng
            $table->foreignId('employee_id')->references('id')->on('employees');                //Người nhập số lượng
            $table->timestamps();                                                               //Ngày nhập số lượng
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dailyquantities');
    }
};
