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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->unique();
            $table->string('code')->unique()->nullable();;
            $table->string('address')->nullable();
            $table->string('home_town')->nullable();
            $table->string('gender')->nullable();
            $table->date('birthday')->nullable();
            $table->string('CCCD')->nullable();
            $table->string('photo')->nullable();
            $table->string('card_photo')->nullable();
            $table->string('marital_status')->nullable();
            $table->date('date_joining')->nullable();
            $table->foreignId('role_id')->references('id')->on('roles');
            $table->foreignId('category_celender_id')->references('id')->on('categories_celender')->nullable();
            $table->string('password')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
