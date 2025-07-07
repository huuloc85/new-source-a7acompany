<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            DROP TRIGGER IF EXISTS after_insert_celenders;
            DROP TRIGGER IF EXISTS after_update_celenders;
            DROP TRIGGER IF EXISTS after_delete_celenders;
        ');

        DB::unprepared('
            CREATE TRIGGER after_insert_celenders
            AFTER INSERT ON celenders
            FOR EACH ROW
            BEGIN
                INSERT INTO schedules (id, title, date, created_at, updated_at) 
                VALUES (NEW.id, NEW.title, NEW.date, NEW.created_at, NEW.updated_at);
            END;
        ');

        DB::unprepared('
            CREATE TRIGGER after_update_celenders
            AFTER UPDATE ON celenders
            FOR EACH ROW
            BEGIN
                UPDATE schedules SET id = NEW.id, title = NEW.title, date = NEW.date, created_at = NEW.created_at, updated_at = NEW.updated_at 
                WHERE id = OLD.id;
            END;
        ');

        DB::unprepared('
            CREATE TRIGGER after_delete_celenders
            AFTER DELETE ON celenders
            FOR EACH ROW
            BEGIN
                DELETE FROM schedules WHERE id = OLD.id;
            END;
        ');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_insert_celenders');
        DB::unprepared('DROP TRIGGER IF EXISTS after_update_celenders');
        DB::unprepared('DROP TRIGGER IF EXISTS after_delete_celenders');

    }
};
