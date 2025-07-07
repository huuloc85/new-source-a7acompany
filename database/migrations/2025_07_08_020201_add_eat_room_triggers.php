<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_insert_eat_room');
        DB::unprepared('DROP TRIGGER IF EXISTS after_update_eat_room');
        DB::unprepared('DROP TRIGGER IF EXISTS after_delete_eat_room');
        DB::unprepared('CREATE TRIGGER after_insert_eat_room AFTER INSERT ON celender_detail_eatroom FOR EACH ROW CALL insert_eat_room_to_schedule_details(NEW.employee_id, NEW.celender_id)');
        DB::unprepared('CREATE TRIGGER after_update_eat_room AFTER UPDATE ON celender_detail_eatroom FOR EACH ROW CALL insert_eat_room_to_schedule_details(NEW.employee_id, NEW.celender_id)');
        DB::unprepared('CREATE TRIGGER after_delete_eat_room AFTER DELETE ON celender_detail_eatroom FOR EACH ROW CALL insert_eat_room_to_schedule_details(OLD.employee_id, OLD.celender_id)');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_insert_eat_room');
        DB::unprepared('DROP TRIGGER IF EXISTS after_update_eat_room');
        DB::unprepared('DROP TRIGGER IF EXISTS after_delete_eat_room');
    }
};
