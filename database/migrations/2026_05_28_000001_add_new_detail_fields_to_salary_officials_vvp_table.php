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
        Schema::table('salary_officials_vvp', function (Blueprint $table) {
            if (! Schema::hasColumn('salary_officials_vvp', 'allowance_professional_detail')) {
                $table->double('allowance_professional_detail', 19, 2)->nullable()->after('allowance_diligence_detail_notice');
            }
            if (! Schema::hasColumn('salary_officials_vvp', 'allowance_professional_detail_notice')) {
                $table->string('allowance_professional_detail_notice', 255)->nullable()->after('allowance_professional_detail');
            }
            if (! Schema::hasColumn('salary_officials_vvp', 'reinforcement_hours_detail')) {
                $table->double('reinforcement_hours_detail', 19, 2)->nullable()->after('overtime_salary_notice');
            }
            if (! Schema::hasColumn('salary_officials_vvp', 'reinforcement_salary')) {
                $table->double('reinforcement_salary', 19, 2)->nullable()->after('reinforcement_hours_detail');
            }
            if (! Schema::hasColumn('salary_officials_vvp', 'reinforcement_salary_notice')) {
                $table->string('reinforcement_salary_notice', 255)->nullable()->after('reinforcement_salary');
            }
            if (! Schema::hasColumn('salary_officials_vvp', 'previous_month_kpi_refund')) {
                $table->double('previous_month_kpi_refund', 19, 2)->nullable()->after('bonuses_for_attendance_notice');
            }
            if (! Schema::hasColumn('salary_officials_vvp', 'previous_month_kpi_refund_notice')) {
                $table->string('previous_month_kpi_refund_notice', 255)->nullable()->after('previous_month_kpi_refund');
            }
            if (! Schema::hasColumn('salary_officials_vvp', 'union_fee')) {
                $table->double('union_fee', 19, 2)->nullable()->after('advance_money_notice');
            }
            if (! Schema::hasColumn('salary_officials_vvp', 'union_fee_notice')) {
                $table->string('union_fee_notice', 255)->nullable()->after('union_fee');
            }
            if (! Schema::hasColumn('salary_officials_vvp', 'daysleave_allowed_notice')) {
                $table->string('daysleave_allowed_notice', 255)->nullable()->after('daysleave_allowed');
            }
            if (! Schema::hasColumn('salary_officials_vvp', 'daysleave_notallowed_notice')) {
                $table->string('daysleave_notallowed_notice', 255)->nullable()->after('daysleave_notallowed');
            }
            if (! Schema::hasColumn('salary_officials_vvp', 'error_serious_notice')) {
                $table->string('error_serious_notice', 255)->nullable()->after('error_serious');
            }
            if (! Schema::hasColumn('salary_officials_vvp', 'error_minor_notice')) {
                $table->string('error_minor_notice', 255)->nullable()->after('error_minor');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_officials_vvp', function (Blueprint $table) {
            $columns = [
                'allowance_professional_detail',
                'allowance_professional_detail_notice',
                'reinforcement_hours_detail',
                'reinforcement_salary',
                'reinforcement_salary_notice',
                'previous_month_kpi_refund',
                'previous_month_kpi_refund_notice',
                'union_fee',
                'union_fee_notice',
                'daysleave_allowed_notice',
                'daysleave_notallowed_notice',
                'error_serious_notice',
                'error_minor_notice',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('salary_officials_vvp', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
