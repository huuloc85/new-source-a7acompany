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
        Schema::create('attendencerecord', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->nullable();
            $table->dateTime('datetime')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('direction')->nullable();
            $table->string('deviceName')->nullable();
            $table->string('deviceSN')->nullable();
            $table->string('employee_Name')->nullable();
            $table->string('cardNo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendencerecord');
    }
};
