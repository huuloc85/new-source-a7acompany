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
        DB::table('attendencerecord')
            ->where('updated_at', '0000-00-00 00:00:00')
            ->update(['updated_at' => now()]);
        Schema::table('attendencerecord', function (Blueprint $table) {
            $table->foreign('employee_code')
                ->references('id')->on('employees')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendencerecord', function (Blueprint $table) {
            $table->dropForeign(['employee_code']);
        });
    }
};
