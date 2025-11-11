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
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('storage_product_id');
            $table->enum('type', ['in', 'out'])->comment('Loại giao dịch: in (nhập), out (xuất)');
            $table->integer('quantity')->comment('Số lượng');
            $table->integer('remaining_quantity')->comment('Số lượng còn lại sau giao dịch');
            $table->text('note')->nullable()->comment('Ghi chú');
            $table->string('employee_id')->nullable()->comment('Nhân viên thực hiện');
            $table->timestamps();

            // Index cho tối ưu
            $table->index('storage_product_id');
            $table->index('employee_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
