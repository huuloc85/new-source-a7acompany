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
        Schema::table('send_stamps', function (Blueprint $table) {
            $table->enum('purpose', ['new', 'additional', 'reprint'])->nullable()->after('manager_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('send_stamps', function (Blueprint $table) {
            $table->dropColumn('purpose');
        });
    }
};
