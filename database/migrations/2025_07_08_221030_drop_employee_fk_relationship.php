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
        Schema::table('login_history', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('dailyquantities_po', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('storage_product', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('send_stamps', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['manager_id']);
        });
        Schema::table('check_employees', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('dailyquantities', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('schedule_details', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('salary_officials_a7a', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('salary_officials_vvp', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('celender_detail_eatroom', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('celender_detail_hnhc', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('celender_detail_wc', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('celender_detail_wc_clean_men', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('celender_detail_wc_clean_women', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add foreign key constraints

        Schema::table('celender_detail_wc_clean_women', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('celender_detail_wc_clean_men', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('celender_detail_wc', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('celender_detail_hnhc', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('celender_detail_eatroom', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('salary_officials_vvp', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('salary_officials_a7a', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('schedule_details', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('dailyquantities', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('check_employees', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('send_stamps', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('manager_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('storage_product', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('dailyquantities_po', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('login_history', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
        });
    }
};
