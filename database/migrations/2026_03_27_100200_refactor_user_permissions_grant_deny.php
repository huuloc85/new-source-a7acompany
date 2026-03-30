<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Refactor: Xóa bảng user_permissions cũ (chỉ grant),
     * tạo 2 bảng mới: user_granted_permissions + user_denied_permissions
     * để hỗ trợ cả Grant và Deny permissions.
     *
     * Quyền hiệu lực = (Quyền từ Role − Denied) ∪ Granted
     */
    public function up(): void
    {
        // 1. Xóa bảng cũ nếu tồn tại
        Schema::dropIfExists('user_permissions');

        // 2. Tạo bảng user_granted_permissions (quyền THÊM ngoài role)
        Schema::create('user_granted_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');  // employees.id is string type
            $table->unsignedBigInteger('permission_id');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->unique(['user_id', 'permission_id'], 'user_granted_perm_unique');
        });

        // 3. Tạo bảng user_denied_permissions (quyền CHẶN từ role)
        Schema::create('user_denied_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');  // employees.id is string type
            $table->unsignedBigInteger('permission_id');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->unique(['user_id', 'permission_id'], 'user_denied_perm_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_denied_permissions');
        Schema::dropIfExists('user_granted_permissions');

        // Khôi phục bảng cũ
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->unsignedBigInteger('permission_id');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->unique(['user_id', 'permission_id'], 'user_permission_unique');
        });
    }
};
