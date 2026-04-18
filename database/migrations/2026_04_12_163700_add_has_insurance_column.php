<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thêm cột has_insurance để lưu trạng thái toggle bảo hiểm.
     * Giải quyết bug: khi FE không gửi has_insurance, BE không biết
     * user có muốn đóng BHXH hay không.
     */
    public function up(): void
    {
        Schema::table('salary_officials_a7a', function (Blueprint $table) {
            $table->boolean('has_insurance')->default(false)->after('insurance');
        });

        Schema::table('salary_officials_vvp', function (Blueprint $table) {
            $table->boolean('has_insurance')->default(false)->after('insurance');
        });

        // Backfill: nếu đã có insurance > 0 → has_insurance = true
        \App\Models\SalaryOfficialA7A::where('insurance', '>', 0)->update(['has_insurance' => true]);
        \App\Models\SalaryOfficialVVP::where('insurance', '>', 0)->update(['has_insurance' => true]);
    }

    public function down(): void
    {
        Schema::table('salary_officials_a7a', function (Blueprint $table) {
            $table->dropColumn('has_insurance');
        });

        Schema::table('salary_officials_vvp', function (Blueprint $table) {
            $table->dropColumn('has_insurance');
        });
    }
};
