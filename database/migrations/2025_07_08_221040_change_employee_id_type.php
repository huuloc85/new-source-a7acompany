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
        Schema::disableForeignKeyConstraints();
        DB::table('employees')->where('birthday', '0000-00-00')->update(['birthday' => null]);
        Schema::table('employees', function (Blueprint $table) {
            $table->string('id', 20)->change();
        });

        Schema::table('login_history', function (Blueprint $table) {
            $table->string('employee_id', 20)->change();
        });
        Schema::table('dailyquantities_po', function (Blueprint $table) {
            $table->string('employee_id', 20)->change();
        });
        DB::statement('ALTER TABLE storage_product COLLATE utf8mb4_unicode_ci;');
        Schema::table('storage_product', function (Blueprint $table) {
            $table->string('employee_id', 20)->change();
        });
        Schema::table('send_stamps', function (Blueprint $table) {
            $table->string('employee_id', 20)->change();
            $table->string('manager_id', 20)->nullable()->change();
        });
        Schema::table('check_employees', function (Blueprint $table) {
            $table->string('employee_id', 20)->change();
        });
        DB::statement('ALTER TABLE dailyquantities COLLATE utf8mb4_unicode_ci;');
        Schema::table('dailyquantities', function (Blueprint $table) {
            $table->string('employee_id', 20)->change();
        });
        Schema::table('schedule_details', function (Blueprint $table) {
            $table->string('employee_id', 20)->change();
        });
        Schema::table('salary_officials_a7a', function (Blueprint $table) {
            $table->string('employee_id', 20)->change();
        });
        Schema::table('salary_officials_vvp', function (Blueprint $table) {
            $table->string('employee_id', 20)->change();
        });
        Schema::table('celender_detail_eatroom', function (Blueprint $table) {
            $table->string('employee_id', 20)->change();
        });
        Schema::table('celender_detail_hnhc', function (Blueprint $table) {
            $table->string('employee_id', 20)->change();
        });
        Schema::table('celender_detail_wc', function (Blueprint $table) {
            $table->string('employee_id', 20)->change();
        });
        Schema::table('celender_detail_wc_clean_men', function (Blueprint $table) {
            $table->string('employee_id', 20)->change();
        });
        Schema::table('celender_detail_wc_clean_women', function (Blueprint $table) {
            $table->string('employee_id', 20)->change();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
        });
        Schema::table('login_history', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->change();
        });
        Schema::table('dailyquantities_po', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->change();
        });
        Schema::table('storage_product', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->change();
        });
        Schema::table('send_stamps', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->change();
            $table->unsignedBigInteger('manager_id')->nullable()->change();
        });
        Schema::table('check_employees', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->change();
        });
        Schema::table('dailyquantities', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->change();
        });
        Schema::table('schedule_details', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->change();
        });
        Schema::table('salary_officials_a7a', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->change();
        });
        Schema::table('salary_officials_vvp', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->change();
        });
        Schema::table('celender_detail_eatroom', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->change();
        });
        Schema::table('celender_detail_hnhc', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->change();
        });
        Schema::table('celender_detail_wc', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->change();
        });
        Schema::table('celender_detail_wc_clean_men', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->change();
        });
        Schema::table('celender_detail_wc_clean_women', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->change();
        });

    }
};
