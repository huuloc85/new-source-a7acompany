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
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });
        Schema::table('roles', function (Blueprint $table) {
            $table->string('id', 50)->change();
        });
        Schema::table('employees', function (Blueprint $table) {
            $table->string('role_id', 50)->change();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });
        Schema::table('roles', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
        });
        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->change();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
        });
    }
};
