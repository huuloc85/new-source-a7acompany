<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    public function up(): void
    {
        $procedure_insert = File::get(database_path('/scripts/procedures/trg_insert_hnhc_to_schedule_details.sql'));
        DB::unprepared($procedure_insert);
        $procedure_delete = File::get(database_path('/scripts/procedures/trg_delete_hnhc_from_schedule_details.sql'));
        DB::unprepared($procedure_delete);

        DB::unprepared('DROP TRIGGER IF EXISTS after_insert_hnhc');
        DB::unprepared('DROP TRIGGER IF EXISTS after_update_hnhc');
        DB::unprepared('DROP TRIGGER IF EXISTS after_delete_hnhc');
        DB::unprepared('CREATE TRIGGER after_insert_hnhc AFTER INSERT ON celender_detail_hnhc FOR EACH ROW CALL trg_insert_hnhc_to_schedule_details(NEW.employee_id, NEW.celender_id)');
        DB::unprepared('CREATE TRIGGER after_update_hnhc AFTER UPDATE ON celender_detail_hnhc FOR EACH ROW CALL trg_insert_hnhc_to_schedule_details(NEW.employee_id, NEW.celender_id)');
        DB::unprepared('CREATE TRIGGER after_delete_hnhc AFTER DELETE ON celender_detail_hnhc FOR EACH ROW CALL trg_delete_hnhc_from_schedule_details(OLD.employee_id, OLD.celender_id)');

    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_insert_hnhc');
        DB::unprepared('DROP TRIGGER IF EXISTS after_update_hnhc');
        DB::unprepared('DROP TRIGGER IF EXISTS after_delete_hnhc');

        DB::unprepared('DROP PROCEDURE IF EXISTS trg_delete_hnhc_from_schedule_details');
        DB::unprepared('DROP PROCEDURE IF EXISTS trg_insert_hnhc_to_schedule_details');

    }
};
