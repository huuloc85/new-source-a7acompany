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
        Schema::create('sidebar_items', function (Blueprint $table) {
            $table->id();

            // Liên kết trực tiếp tới permission
            $table->foreignId('permission_id')
                ->constrained('permissions')
                ->onDelete('cascade');
            $table->string('key')->unique(); // Khóa duy nhất cho item sidebar
            $table->string('title');             // Nhãn hiển thị
            $table->string('icon')->nullable();  // Icon nếu có
            $table->string('path')->nullable();  // Route path nếu có

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sidebar_items');
    }
};
