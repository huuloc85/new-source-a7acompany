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
            // Thông tin người ký và thời gian ký cho supervisor
            $table->string('supervisor_approved_by')->nullable()->comment('ID người tổ trưởng đã ký');
            $table->datetime('supervisor_approved_at')->nullable()->comment('Thời gian tổ trưởng ký');

            // Thông tin người ký và thời gian ký cho manager
            $table->string('manager_approved_by')->nullable()->comment('ID người quản lý đã ký');
            $table->datetime('manager_approved_at')->nullable()->comment('Thời gian quản lý ký');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_forms', function (Blueprint $table) {
            $table->dropColumn([
                'supervisor_approved_by',
                'supervisor_approved_at',
                'manager_approved_by',
                'manager_approved_at',
            ]);
        });
    }
};
