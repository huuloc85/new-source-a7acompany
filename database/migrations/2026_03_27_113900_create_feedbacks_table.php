<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');           // Người gửi góp ý
            $table->string('type')->default('suggestion'); // suggestion, bug, complaint, other
            $table->string('subject');               // Tiêu đề
            $table->text('content');                  // Nội dung góp ý
            $table->string('status')->default('pending'); // pending, reviewed, resolved, rejected
            $table->text('admin_reply')->nullable();  // Phản hồi từ admin
            $table->string('replied_by')->nullable(); // Admin đã phản hồi
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');

            $table->index(['employee_id', 'status']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
