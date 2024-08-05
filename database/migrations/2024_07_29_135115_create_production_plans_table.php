<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('production_plans', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('material_name', 255)->nullable();                   // Tên Nguyên Vật Liệu
            $table->integer('production_plan')->nullable();                     // Kế Hoạch Sản Xuất (PCS)
            $table->double('planned_material', 16, 2)->nullable();              // Dự Định Vật Liệu (KG)
            $table->string('packaging_type', 255)->nullable();                  // Loại Bao Bì
            $table->integer('packaging_count_per_box')->nullable();             // Số Bao Bì/Thùng
            $table->double('total_packaging', 16, 2)->nullable();               // Tổng Bao Bì
            $table->string('box_type', 255)->nullable();                        // Loại Thùng
            $table->integer('products_per_box')->nullable();                    // Sản Phẩm/Thùng
            $table->integer('box_quantity')->nullable();                        // Số Lượng Thùng
            $table->double('product_density', 16, 2)->nullable();               // Tỷ Trọng Sản Phẩm (G)
            $table->integer('daily_production_plan')->nullable();               // Kế Hoạch SX/Ngày
            $table->integer('cavity_count')->nullable();                        // Số Cavity
            $table->double('cycle', 16, 2)->nullable();                         // Chu Kỳ
            $table->double('ton', 16, 2)->nullable();                           // Tấn
            $table->string('machine', 255)->nullable();                         // Máy
            $table->double('machine_run_days', 16, 2)->nullable();              // Số Ngày Chạy Máy
            $table->double('remaining_production_days', 16, 2)->nullable();     // Số Ngày Còn SX (Ngày)
            $table->double('remaining_production_quantity', 16, 2)->nullable(); // Số Lượng Còn SX (PCS)
            $table->double('produced_quantity', 16, 2)->nullable();             // Số Lượng Đã SX (PCS)
            $table->string('month', 255)->nullable();                           // Tháng
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_plans');
    }
};
