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
        // 1. Thêm NN vào ENUM của bảng schedule_details
        DB::statement("ALTER TABLE schedule_details MODIFY COLUMN hnhc ENUM('N','D','X','TC','LN','NN') DEFAULT NULL;");

        // 2. Cập nhật lại Procedure trg_insert_hnhc_to_schedule_details trên host
        $procedure_insert = \Illuminate\Support\Facades\File::get(database_path('/scripts/procedures/trg_insert_hnhc_to_schedule_details.sql'));
        // Phải thay thế câu lệnh CREATE bằng DROP CREATE cho chắc ăn, nhưng trong file sql đã có lệnh drop.
        DB::unprepared($procedure_insert);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE schedule_details MODIFY COLUMN hnhc ENUM('N','D','X','TC','LN') DEFAULT NULL;");
    }
};
