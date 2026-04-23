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
        DB::statement("ALTER TABLE schedule_details MODIFY COLUMN hnhc ENUM('N','D','X','TC','LN','NN') DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE schedule_details MODIFY COLUMN hnhc ENUM('N','D','X','TC','LN') DEFAULT NULL;");
    }
};
