<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $procedure_insert = File::get(database_path('/scripts/procedures/insert_hnhc_to_schedule_details.sql'));
        $procedure_run_all = File::get(database_path('/scripts/procedures/run_all_insert_hnhc_to_schedule_details.sql'));
        DB::unprepared($procedure_insert);
        DB::unprepared($procedure_run_all);
        DB::unprepared('CALL run_all_insert_hnhc_to_schedule_details()');
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS insert_hnhc_to_schedule_details');
        DB::unprepared('DROP PROCEDURE IF EXISTS run_all_insert_hnhc_to_schedule_details');
    }
};
