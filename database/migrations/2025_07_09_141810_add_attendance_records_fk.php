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
        DB::table('attendance_records')
            ->where('updated_at', '0000-00-00 00:00:00')
            ->update(['updated_at' => now()]);
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
    }
};
