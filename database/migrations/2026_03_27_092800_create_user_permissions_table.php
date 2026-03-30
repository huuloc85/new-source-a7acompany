<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tạo bảng pivot user_permissions cho phân quyền trực tiếp.
     * Cho phép Super Admin gán thêm quyền trực tiếp cho từng admin user,
     * bổ sung vào quyền kế thừa từ role.
     */
    public function up(): void
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');  // employees.id is string type
            $table->unsignedBigInteger('permission_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            // Unique constraint - mỗi user chỉ có 1 lần gán 1 permission
            $table->unique(['user_id', 'permission_id'], 'user_permission_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};
