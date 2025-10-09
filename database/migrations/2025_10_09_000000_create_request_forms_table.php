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
            // Primary & Basic Info
            $table->id();
            $table->string('employee_id', 20)->comment('ID nhân viên tạo đơn');
            $table->string('authorized_employee_id', 20)->nullable()->comment('ID nhân viên được ủy quyền (giay_uy_quyen)');
            $table->string('supervisor_id', 20)->nullable()->comment('ID tổ trưởng duyệt đơn');

            // Form Content
            $table->string('type')->comment('Loại đơn: giay_uy_quyen, don_xin_nghi_phep, don_xin_tu_chuc, don_xin_nghi_viec, don_xin_di_tre_ve_som');
            $table->string('title')->comment('Tiêu đề đơn');
            $table->text('content')->comment('Nội dung đơn');
            $table->json('form_data')->comment('Dữ liệu form JSON');

            // Status & Approval
            $table->enum('status', ['pending', 'approved', 'rejected', 'authorized_approved'])->default('pending')->comment('Trạng thái đơn');
            $table->string('approved_by', 20)->nullable()->comment('ID người duyệt cuối');
            $table->text('rejection_reason')->nullable()->comment('Lý do từ chối');

            // Digital Signatures - Giấy Ủy Quyền
            $table->string('digital_signature_delegator')->nullable()->comment('Chữ ký người ủy quyền');
            $table->string('delegator_approved_by', 20)->nullable()->comment('ID người ủy quyền đã ký');
            $table->datetime('delegator_approved_at')->nullable()->comment('Thời gian ký');

            $table->string('digital_signature_authorized')->nullable()->comment('Chữ ký người được ủy quyền');
            $table->string('authorized_approved_by', 20)->nullable()->comment('ID người được ủy quyền đã ký');
            $table->datetime('authorized_approved_at')->nullable()->comment('Thời gian ký');

            // Digital Signatures - Đơn Thường
            $table->string('digital_signature_applicant')->nullable()->comment('Chữ ký người làm đơn');

            $table->string('digital_signature_supervisor')->nullable()->comment('Chữ ký tổ trưởng');
            $table->string('supervisor_approved_by', 20)->nullable()->comment('ID tổ trưởng đã ký');
            $table->datetime('supervisor_approved_at')->nullable()->comment('Thời gian ký');

            $table->string('digital_signature_manager')->nullable()->comment('Chữ ký giám đốc');
            $table->string('manager_approved_by', 20)->nullable()->comment('ID giám đốc đã ký');
            $table->datetime('manager_approved_at')->nullable()->comment('Thời gian ký');

            // Timestamps
            $table->timestamp('submitted_at')->comment('Thời gian nộp đơn');
            $table->timestamp('approved_at')->nullable()->comment('Thời gian duyệt');
            $table->timestamp('rejected_at')->nullable()->comment('Thời gian từ chối');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('authorized_employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('supervisor_id')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('employees')->onDelete('set null');

            // Indexes
            $table->index('employee_id');
            $table->index('authorized_employee_id');
            $table->index('supervisor_id');
            $table->index('type');
            $table->index('status');
            $table->index('submitted_at');
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
