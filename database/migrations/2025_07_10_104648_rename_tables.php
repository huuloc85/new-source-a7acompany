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
        Schema::rename('categories_celender', 'calendar_categories');
        Schema::rename('dailyquantities', 'daily_quantities');
        Schema::rename('dailyquantities_po', 'daily_quantities_po');
        Schema::rename('totalmonthquantities', 'total_month_quantities');
        Schema::rename('totaldailyquantities', 'total_daily_quantities');
        Schema::rename('totaldailyquantities_po', 'total_daily_quantities_po');
        Schema::rename('attendencerecord', 'attendance_records');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('calendar_categories', 'categories_celender');
        Schema::rename('daily_quantities', 'dailyquantities');
        Schema::rename('daily_quantities_po', 'dailyquantities_po');
        Schema::rename('total_month_quantities', 'totalmonthquantities');
        Schema::rename('total_daily_quantities', 'totaldailyquantities');
        Schema::rename('total_daily_quantities_po', 'totaldailyquantities_po');
        Schema::rename('attendance_records', 'attendencerecord');
    }
};
