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
        Schema::create('mold_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('press_size')->nullable();          //Kích cở máy ép
            $table->integer('press_quantity')->nullable();     //Số lượng máy
            $table->string('moldSize')->nullable();            //Kích cở khuôn
            $table->integer('plan_time')->nullable();          //Dự định Thời gian hoạt động
            $table->integer('real_time')->nullable();          //Thực tích Thời gian hoạt động
            $table->integer('detail')->nullable();             //chi tiết
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mold_parameters');
    }
};
