<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = DB::select('SELECT TABLE_NAME as table_name FROM information_schema.tables WHERE table_schema = ?', [config('database.connections.mysql.database')]);
        foreach ($tables as $table) {
            DB::statement('ALTER TABLE `'.$table->table_name.'` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
