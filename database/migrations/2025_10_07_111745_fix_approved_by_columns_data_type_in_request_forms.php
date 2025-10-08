<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Chuẩn hóa data type từ varchar(255) → varchar(20) cho consistency
        DB::statement('ALTER TABLE request_forms MODIFY supervisor_approved_by VARCHAR(20)');
        DB::statement('ALTER TABLE request_forms MODIFY manager_approved_by VARCHAR(20)');
        DB::statement('ALTER TABLE request_forms MODIFY delegator_approved_by VARCHAR(20)');
        DB::statement('ALTER TABLE request_forms MODIFY authorized_approved_by VARCHAR(20)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback về varchar(255)
        DB::statement('ALTER TABLE request_forms MODIFY supervisor_approved_by VARCHAR(255)');
        DB::statement('ALTER TABLE request_forms MODIFY manager_approved_by VARCHAR(255)');
        DB::statement('ALTER TABLE request_forms MODIFY delegator_approved_by VARCHAR(255)');
        DB::statement('ALTER TABLE request_forms MODIFY authorized_approved_by VARCHAR(255)');
    }
};
