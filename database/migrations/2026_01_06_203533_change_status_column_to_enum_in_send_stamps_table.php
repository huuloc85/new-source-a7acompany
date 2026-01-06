<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Thay đổi column status từ string thành enum
        DB::statement("ALTER TABLE send_stamps MODIFY COLUMN status ENUM('pending', 'approve', 'rejected') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Chuyển lại thành string nếu cần rollback
        DB::statement("ALTER TABLE send_stamps MODIFY COLUMN status VARCHAR(255) NOT NULL");
    }
};
