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
            // Thêm cột supervisor_id - ID của supervisor sẽ duyệt đơn này
            $table->string('supervisor_id', 20)->nullable()->after('authorized_employee_id');

            // Foreign key tới bảng employees
            $table->foreign('supervisor_id')
                ->references('id')
                ->on('employees')
                ->onDelete('set null');

            // Index để tăng tốc query
            $table->index('supervisor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_forms', function (Blueprint $table) {
            $table->dropForeign(['supervisor_id']);
            $table->dropIndex(['supervisor_id']);
            $table->dropColumn('supervisor_id');
        });
    }
};
