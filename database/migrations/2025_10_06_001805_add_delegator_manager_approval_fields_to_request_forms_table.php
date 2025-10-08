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
        Schema::table('request_forms', function (Blueprint $table) {
            // Thêm trường approval cho đơn ủy quyền
            $table->string('delegator_approved_by')->nullable()->comment('ID người ủy quyền đã ký');
            $table->datetime('delegator_approved_at')->nullable()->comment('Thời gian người ủy quyền ký');
            $table->string('authorized_approved_by')->nullable()->comment('ID người được ủy quyền đã ký');
            $table->datetime('authorized_approved_at')->nullable()->comment('Thời gian người được ủy quyền ký');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_forms', function (Blueprint $table) {
            $table->dropColumn([
                'delegator_approved_by',
                'delegator_approved_at',
                'authorized_approved_by',
                'authorized_approved_at',
            ]);
        });
    }
};
