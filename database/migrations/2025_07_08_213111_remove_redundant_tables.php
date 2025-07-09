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
        Schema::dropIfExists('mold_parameters');
        Schema::dropIfExists('timekeeping_parttimes');
        Schema::dropIfExists('salary_parttimes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::create('mold_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('press_size')->nullable();
            $table->integer('press_quantity')->nullable();
            $table->string('moldSize')->nullable();
            $table->integer('plan_time')->nullable();
            $table->integer('real_time')->nullable();
            $table->integer('detail')->nullable();
            $table->timestamps();
        });
        Schema::create('salary_parttimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salaries_manager_id')->references('id')->on('salaries_manager');
            $table->foreignId('employee_id')->references('id')->on('employees');

            $table->double('salary_total', 19, 2)->nullable();
            $table->double('insurance', 19, 2)->nullable();
            $table->double('advance_money', 19, 2)->nullable();
            $table->double('company_insurance', 19, 2)->nullable();
            $table->double('debt_last', 19, 2)->nullable();
            $table->double('actually_received')->nullable();

            $table->double('total_day', 19, 2)->nullable();
            $table->double('total_night', 19, 2)->nullable();
            $table->double('total_overtime', 19, 2)->nullable();
            $table->double('workday_money', 19, 2)->nullable();
            $table->double('worknight_money', 19, 2)->nullable();
            $table->double('allowance_outwork', 19, 2)->nullable();
            $table->double('salary_total_2', 19, 2)->nullable();

            $table->double('holidays_count', 19, 2)->nullable();
            $table->double('paid_holidays_count', 19, 2)->nullable();
            $table->double('outwork_day_count', 19, 2)->nullable();
            $table->double('increase_day', 19, 2)->nullable();
            $table->double('increase_night', 19, 2)->nullable();
            $table->double('annual_leave', 19, 2)->nullable();
            $table->timestamps();
        });
        Schema::create('timekeeping_parttimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_parttime_id')->references('id')->on('salary_parttimes');
            $table->date('timekeeping_date')->nullable();
            $table->double('timekeeping_day', 19, 2)->nullable();
            $table->double('timekeeping_night', 19, 2)->nullable();
            $table->double('timekeeping_overtime', 19, 2)->nullable();
            $table->timestamps();
        });

    }
};
