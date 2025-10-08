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
            // Composite index cho query phổ biến: employee + status
            $table->index(['employee_id', 'status'], 'idx_employee_status');

            // Index cho authorized employee lookup
            $table->index(['authorized_employee_id', 'status'], 'idx_authorized_status');

            // Index cho filter theo type
            $table->index(['type', 'status'], 'idx_type_status');

            // Index cho date range queries (đã có nhưng thêm status)
            $table->index(['submitted_at', 'status'], 'idx_submitted_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_forms', function (Blueprint $table) {
            $table->dropIndex('idx_employee_status');
            $table->dropIndex('idx_authorized_status');
            $table->dropIndex('idx_type_status');
            $table->dropIndex('idx_submitted_status');
        });
    }
};
