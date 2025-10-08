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
        // Thay đổi ENUM để thêm 'authorized_approved'
        DB::statement("ALTER TABLE request_forms MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'authorized_approved') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback về ENUM cũ
        DB::statement("ALTER TABLE request_forms MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    }
};
