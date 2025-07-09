<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('UPDATE employees SET id = code');
        Schema::table('employees', function ($table) {
            $table->dropColumn('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::beginTransaction();
        try {
            Schema::table('employees', function ($table) {
                $table->string('code', 20)->after('id');
            });
            DB::statement('UPDATE employees SET code = id');

            $employees = DB::table('employees')->orderBy('id')->get();
            $map = [];
            $counter = 1;

            foreach ($employees as $emp) {
                $newId = (string) $counter++;
                $map[$emp->id] = $newId;
            }
            foreach ($map as $oldId => $newId) {
                DB::table('employees')->where('id', $oldId)->update(['id' => $newId]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
};
