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
        Schema::table('request_forms', function (Blueprint $table) {
            $table->string('authorized_employee_id', 20)->nullable()->after('employee_id');
            $table->foreign('authorized_employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->index('authorized_employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_forms', function (Blueprint $table) {
            $table->dropForeign(['authorized_employee_id']);
            $table->dropIndex(['authorized_employee_id']);
            $table->dropColumn('authorized_employee_id');
        });
    }
};
