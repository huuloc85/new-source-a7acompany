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
        Schema::table('request_forms', function (Blueprint $table) {
            // Xóa trường digital_signature cũ
            $table->dropColumn('digital_signature');

            // Thêm 5 loại chữ ký mới
            // Cho đơn Ủy Quyền (giay_uy_quyen) - 2 chữ ký
            $table->string('digital_signature_delegator')->nullable()->comment('Chữ ký bên ủy quyền');
            $table->string('digital_signature_authorized')->nullable()->comment('Chữ ký bên được ủy quyền');

            // Cho các đơn khác - 3 chữ ký
            $table->string('digital_signature_applicant')->nullable()->comment('Chữ ký người làm đơn');
            $table->string('digital_signature_supervisor')->nullable()->comment('Chữ ký tổ trưởng/giám sát');
            $table->string('digital_signature_manager')->nullable()->comment('Chữ ký quản lý/phê duyệt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_forms', function (Blueprint $table) {
            // Xóa 5 trường chữ ký mới
            $table->dropColumn([
                'digital_signature_delegator',
                'digital_signature_authorized',
                'digital_signature_applicant',
                'digital_signature_supervisor',
                'digital_signature_manager',
            ]);

            // Thêm lại trường digital_signature cũ
            $table->string('digital_signature')->nullable();
        });
    }
};
