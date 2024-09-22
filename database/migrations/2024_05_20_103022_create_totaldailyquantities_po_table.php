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
        Schema::create('totaldailyquantities_po', function (Blueprint $table) {
            $table->id();                                                                       //ID của số liệu hàng ngày
            $table->foreignId('product_id')->references('id')->on('products');                  //ID của sản phẩm
            $table->integer('status')->nullable();                                              //Trạng thái nhập (*)
            $table->date('date')->nullable();                                                   //Ngày
            $table->integer('totalQuan')->nullable();                                           //Tổng số lượng/ngày
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('totaldailyquantities_po');
    }
};
