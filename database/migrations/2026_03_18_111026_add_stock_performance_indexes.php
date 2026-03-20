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
            $table->index('barcode');
            $table->index(['product_id', 'lot', 'bin']);
        });

        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->index(['storage_product_id', 'type']);
            $table->index(['type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('storage_product', function (Blueprint $table) {
            $table->dropIndex(['barcode']);
            $table->dropIndex(['product_id', 'lot', 'bin']);
        });

        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropIndex(['storage_product_id', 'type']);
            $table->dropIndex(['type', 'created_at']);
        });
    }
};
