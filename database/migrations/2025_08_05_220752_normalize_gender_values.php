<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::table('employees')
            ->where('gender', 'Nam')
            ->update(['gender' => 'male']);

        DB::table('employees')
            ->where('gender', 'Nữ')
            ->update(['gender' => 'female']);

        DB::table('employees')
            ->whereNull('gender')
            ->update(['gender' => 'other']);
        DB::table('employees')
            ->where('gender', '')
            ->update(['gender' => 'other']);

        Schema::table('employees', function (Blueprint $table) {
            $table->enum('gender', ['male', 'female', 'other'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('gender')->nullable()->change();
        });

        DB::table('employees')
            ->where('gender', 'male')
            ->update(['gender' => 'Nam']);

        DB::table('employees')
            ->where('gender', 'female')
            ->update(['gender' => 'Nữ']);

        DB::table('employees')
            ->where('gender', 'other')
            ->update(['gender' => null]);
    }
};
