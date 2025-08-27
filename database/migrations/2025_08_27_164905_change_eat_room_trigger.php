<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $procedure_insert = File::get(database_path('/scripts/procedures/trg_insert_eat_room_to_schedule_details.sql'));
        DB::unprepared($procedure_insert);

        $procedure_delete = File::get(database_path('/scripts/procedures/trg_delete_eat_room_from_schedule_details.sql'));
        DB::unprepared($procedure_delete);

        DB::unprepared('DROP TRIGGER IF EXISTS after_insert_eat_room');
        DB::unprepared('DROP TRIGGER IF EXISTS after_update_eat_room');
        DB::unprepared('DROP TRIGGER IF EXISTS after_delete_eat_room');
        DB::unprepared('CREATE TRIGGER after_insert_eat_room AFTER INSERT ON celender_detail_eatroom FOR EACH ROW CALL trg_insert_eat_room_to_schedule_details(NEW.employee_id, NEW.celender_id)');
        DB::unprepared('CREATE TRIGGER after_update_eat_room AFTER UPDATE ON celender_detail_eatroom FOR EACH ROW CALL trg_insert_eat_room_to_schedule_details(NEW.employee_id, NEW.celender_id)');
        DB::unprepared('CREATE TRIGGER after_delete_eat_room AFTER DELETE ON celender_detail_eatroom FOR EACH ROW CALL trg_delete_eat_room_from_schedule_details(OLD.employee_id, OLD.celender_id)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_insert_eat_room');
        DB::unprepared('DROP TRIGGER IF EXISTS after_update_eat_room');
        DB::unprepared('DROP TRIGGER IF EXISTS after_delete_eat_room');

        DB::unprepared('DROP PROCEDURE IF EXISTS trg_insert_eat_room_to_schedule_details');
        DB::unprepared('DROP PROCEDURE IF EXISTS trg_delete_eat_room_from_schedule_details');
    }
};
