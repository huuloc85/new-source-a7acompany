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
        Schema::table('storage_product', function (Blueprint $table) {
            // Drop foreign key constraint trước
            $table->dropForeign('storage_product_employee_id_foreign');
        });

        Schema::table('storage_product', function (Blueprint $table) {
            // Đổi cột thành nullable
            $table->string('employee_id')->nullable()->change();
        });

        Schema::table('storage_product', function (Blueprint $table) {
            // Thêm lại foreign key constraint với nullable
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('storage_product', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['employee_id']);

            // Đổi cột về không nullable
            $table->string('employee_id')->nullable(false)->change();

            // Thêm lại foreign key constraint
            $table->foreign('employee_id')->references('id')->on('employees');
        });
    }
};
