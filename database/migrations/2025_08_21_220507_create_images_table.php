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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Title of the image file');
            $table->string('path')->comment('Path to the image file');
            $table->boolean('is_show')->default(true)->comment('Whether the image is visible');
            $table->string('employee_id', 20);
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->comment('ID of the user who uploaded the image');
            $table->string('type')->comment('Type of the image file');
            $table->string('description')->nullable()->comment('Description of the image');
            $table->integer('size')->comment('Size of the image file in bytes');
            $table->string('extension')->comment('File extension of the image');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
