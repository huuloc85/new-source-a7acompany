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
        Schema::create('timekeeping_officials_a7a', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_official_a7a_id')->references('id')->on('salary_officials_a7a');
            $table->date('timekeeping_date');                               //ngày chấm công
            $table->double('timekeeping_day', 19, 2)->nullable();           //số giờ làm ngày
            $table->double('timekeeping_night', 19, 2)->nullable();         //số giờ làm đêm
            $table->double('timekeeping_overtime', 19, 2)->nullable();      //số giờ tăng ca
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timekeeping_officials_a7a');
    }
};
