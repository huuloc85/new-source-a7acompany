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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('name');

            $table->enum('type', ['admin', 'employee', 'both'])->default('admin');
            $table->enum('display_area', ['home', 'sidebar', 'both'])->default('both');

            $table->timestamps();

            // unique theo cặp
            $table->unique(['key', 'display_area'], 'permissions_key_display_area_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
