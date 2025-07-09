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
        Schema::table('attendencerecord', function (Blueprint $table) {
            $table->renameColumn('employee_code', 'employee_id');
            $table->rename('attendance_records');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->renameColumn('employee_id', 'employee_code');
            $table->rename('attendencerecord');
        });
    }
};
