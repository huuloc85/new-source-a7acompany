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
        Schema::create('request_forms', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id', 20);
            $table->string('type'); // giay_uy_quyen, don_xin_tu_chuc, don_xin_nghi_viec, don_xin_nghi_phep, don_xin_di_tre_ve_som
            $table->string('title');
            $table->text('content');
            $table->json('form_data'); // Lưu dữ liệu form cụ thể cho từng loại đơn
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('approved_by', 20)->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('employees')->onDelete('set null');

            $table->index(['employee_id', 'type']);
            $table->index(['status', 'submitted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_forms');
    }
};
