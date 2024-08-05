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
            $table->foreignId('product_id')->references('id')->on('products');
            $table->string('material_name');                                        // Tên Nguyên Vật Liệu
            $table->integer('production_plan_pcs');                                 // Kế Hoạch Sản Xuất (PCS)
            $table->float('planned_material_kg');                                   // Dự Định Vật Liệu (KG)
            $table->string('packaging_type');                                       // Loại Bao Bì
            $table->integer('packaging_count_per_box');                             // Số Bao Bì/Thùng
            $table->integer('total_packaging');                                     // Tổng Bao Bì
            $table->string('box_type');                                             // Loại Thùng
            $table->integer('products_per_box');                                    // Sản Phẩm/Thùng
            $table->integer('box_quantity');                                        // Số Lượng Thùng
            $table->float('product_density');                                       // Tỷ Trọng Sản Phẩm (G)
            $table->integer('daily_production_plan');                               // Kế Hoạch SX/Ngày
            $table->integer('cavity_count');                                        // Số Cavity
            $table->float('cycle');                                                 // Chu Kỳ
            $table->float('ton');                                                   // Tấn
            $table->string('machine');                                              // Máy
            $table->integer('machine_run_days');                                    // Số Ngày Chạy Máy
            $table->integer('remaining_production_days');                           // Số Ngày Còn SX (Ngày)
            $table->integer('remaining_production_quantity_pcs');                   // Số Lượng Còn SX (PCS)
            $table->integer('produced_quantity_pcs');                               // Số Lượng Đã SX (PCS)
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
