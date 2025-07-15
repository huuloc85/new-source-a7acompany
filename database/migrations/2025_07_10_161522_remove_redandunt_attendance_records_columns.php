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
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropColumn([
                'direction',
                'deviceName',
                'deviceSN',
                'employee_name',
                'cardNo',
            ]);
        });
        DB::unprepared('DROP TRIGGER IF EXISTS trig_after_insert_datetime');
        DB::unprepared('DROP TRIGGER IF EXISTS trig_after_update_datetime');

        DB::unprepared('
            CREATE TRIGGER trig_after_insert_datetime
            BEFORE INSERT ON attendance_records
            FOR EACH ROW
            BEGIN
                SET NEW.date = DATE(NEW.datetime);
                SET NEW.time = TIME(NEW.datetime);
            END
        ');
        DB::unprepared('
            CREATE TRIGGER trig_after_update_datetime
            BEFORE UPDATE ON attendance_records
            FOR EACH ROW
            BEGIN
                SET NEW.date = DATE(NEW.datetime);
                SET NEW.time = TIME(NEW.datetime);
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->string('direction')->nullable();
            $table->string('deviceName')->nullable();
            $table->string('deviceSN')->nullable();
            $table->string('employee_name')->nullable();
            $table->string('cardNo')->nullable();
        });
    }
};
