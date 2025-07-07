<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_insert_wc_clean_women');
        DB::unprepared('DROP TRIGGER IF EXISTS after_update_wc_clean_women');
        DB::unprepared('DROP TRIGGER IF EXISTS after_delete_wc_clean_women');
        DB::unprepared('CREATE TRIGGER after_insert_wc_clean_women AFTER INSERT ON celender_detail_wc_clean_women FOR EACH ROW CALL insert_wc_clean_women_to_schedule_details(NEW.employee_id, NEW.celender_id)');
        DB::unprepared('CREATE TRIGGER after_update_wc_clean_women AFTER UPDATE ON celender_detail_wc_clean_women FOR EACH ROW CALL insert_wc_clean_women_to_schedule_details(NEW.employee_id, NEW.celender_id)');
        DB::unprepared('CREATE TRIGGER after_delete_wc_clean_women AFTER DELETE ON celender_detail_wc_clean_women FOR EACH ROW CALL insert_wc_clean_women_to_schedule_details(OLD.employee_id, OLD.celender_id)');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_insert_wc_clean_women');
        DB::unprepared('DROP TRIGGER IF EXISTS after_update_wc_clean_women');
        DB::unprepared('DROP TRIGGER IF EXISTS after_delete_wc_clean_women');
    }
};
