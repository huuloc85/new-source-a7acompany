<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $procedure_insert = File::get(database_path('/scripts/procedures/insert_wc_clean_men_to_schedule_details.sql'));
        DB::unprepared($procedure_insert);
        DB::unprepared('CALL insert_wc_clean_men_to_schedule_details()');
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS insert_wc_clean_men_to_schedule_details');
    }
};
