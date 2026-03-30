<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Refactor RBAC & Sidebar structure:
     * 1. Add unique constraint to role_permission
     * 2. Drop path, url, sort_order from sidebar_items, then re-add url
     * 3. Add icon, url, sort_order to permissions
     */
    public function up(): void
    {
        // 1. Unique constraint cho role_permission
        try {
            Schema::table('role_permission', function (Blueprint $table) {
                $table->unique(['role_id', 'permission_id'], 'role_permission_unique');
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // Index already exists, skip
        }

        // 2. Drop path, url, sort_order từ sidebar_items
        Schema::table('sidebar_items', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('sidebar_items', 'path')) {
                $columns[] = 'path';
            }
            if (Schema::hasColumn('sidebar_items', 'url')) {
                $columns[] = 'url';
            }
            if (Schema::hasColumn('sidebar_items', 'sort_order')) {
                $columns[] = 'sort_order';
            }
            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });

        // 3. Thêm lại url cho sidebar_items
        if (! Schema::hasColumn('sidebar_items', 'url')) {
            Schema::table('sidebar_items', function (Blueprint $table) {
                $table->string('url')->nullable()->after('icon');
            });
        }

        // 4. Thêm icon, url, sort_order cho permissions
        if (! Schema::hasColumn('permissions', 'icon')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->string('icon')->nullable()->after('display_area');
            });
        }

        if (! Schema::hasColumn('permissions', 'url')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->string('url')->nullable()->after('icon');
            });
        }

        if (! Schema::hasColumn('permissions', 'sort_order')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('url');
            });
        }
    }

    public function down(): void
    {
        // Rollback permissions columns
        Schema::table('permissions', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('permissions', 'sort_order')) {
                $columns[] = 'sort_order';
            }
            if (Schema::hasColumn('permissions', 'url')) {
                $columns[] = 'url';
            }
            if (Schema::hasColumn('permissions', 'icon')) {
                $columns[] = 'icon';
            }
            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });

        // Rollback sidebar_items: drop url, re-add path, url, sort_order
        Schema::table('sidebar_items', function (Blueprint $table) {
            if (Schema::hasColumn('sidebar_items', 'url')) {
                $table->dropColumn('url');
            }
        });

        Schema::table('sidebar_items', function (Blueprint $table) {
            $table->string('path')->nullable()->after('icon');
            $table->string('url')->nullable()->after('path');
            $table->integer('sort_order')->default(0)->after('url');
        });

        // Rollback role_permission unique
        try {
            Schema::table('role_permission', function (Blueprint $table) {
                $table->dropUnique('role_permission_unique');
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // Index doesn't exist, skip
        }
    }
};
