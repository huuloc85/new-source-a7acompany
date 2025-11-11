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
        Schema::table('storage_product', function (Blueprint $table) {
            $table->integer('quantity')->default(0)->after('bin')->comment('Số lượng tồn kho');
            $table->string('barcode')->nullable()->after('quantity')->comment('Mã vạch sản phẩm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('storage_product', function (Blueprint $table) {
            $table->dropColumn(['quantity', 'barcode']);
        });
    }
};
