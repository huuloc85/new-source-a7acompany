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
        // Clone table structure
        DB::statement('CREATE TABLE schedules LIKE celenders');
        Schema::table('schedules', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Copy data
        DB::statement('INSERT INTO schedules (id, title, date, created_at, updated_at) SELECT * FROM celenders');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the schedules table
        Schema::dropIfExists('schedules');
    }
};
