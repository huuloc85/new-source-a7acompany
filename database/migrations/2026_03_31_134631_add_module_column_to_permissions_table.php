<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thêm cột `module` vào bảng permissions để gom nhóm quyền.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('permissions', 'module')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->string('module', 100)->nullable()->after('type');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('permissions', 'module')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->dropColumn('module');
            });
        }
    }
};
