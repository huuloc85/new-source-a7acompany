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
        Schema::table('salary_officials_a7a', function (Blueprint $table) {
            if (!Schema::hasColumn('salary_officials_a7a', 'employee_type')) {
                $table->string('employee_type', 20)->nullable()->comment('Loại nhân viên (office / worker) ghi đè theo tháng');
            }
        });

        Schema::table('salary_officials_vvp', function (Blueprint $table) {
            if (!Schema::hasColumn('salary_officials_vvp', 'employee_type')) {
                $table->string('employee_type', 20)->nullable()->comment('Loại nhân viên (office / worker) ghi đè theo tháng');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_officials_a7a', function (Blueprint $table) {
            if (Schema::hasColumn('salary_officials_a7a', 'employee_type')) {
                $table->dropColumn('employee_type');
            }
        });

        Schema::table('salary_officials_vvp', function (Blueprint $table) {
            if (Schema::hasColumn('salary_officials_vvp', 'employee_type')) {
                $table->dropColumn('employee_type');
            }
        });
    }
};
