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
        Schema::create('schedule_details', function (Blueprint $table) {
            $table->date('date')->index();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('cascade');

            $table->boolean('is_wc_clean_men')->default(false);
            $table->boolean('is_wc_clean_women')->default(false);
            $table->boolean('is_wc_trash')->default(false);
            $table->boolean('is_eat_room')->default(false);
            $table->enum('hnhc', ['N', 'D', 'X', 'TC', 'LN'])->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->primary(['date', 'employee_id', 'schedule_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_details');
    }
};
