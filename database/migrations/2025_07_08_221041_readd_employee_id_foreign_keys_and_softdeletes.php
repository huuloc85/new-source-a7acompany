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
        // Re-add foreign key constraints with updated column type
        Schema::table('celender_detail_wc_clean_women', function (Blueprint $table) {
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('cascade')->onUpdate('cascade');
            if (! Schema::hasColumn('celender_detail_wc_clean_women', 'deleted_at')) {
                $table->softDeletes();
            }
        });
        Schema::table('celender_detail_wc_clean_men', function (Blueprint $table) {
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('cascade')->onUpdate('cascade');
            if (! Schema::hasColumn('celender_detail_wc_clean_men', 'deleted_at')) {
                $table->softDeletes();
            }
        });
        Schema::table('celender_detail_wc', function (Blueprint $table) {
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('cascade')->onUpdate('cascade');
            if (! Schema::hasColumn('celender_detail_wc', 'deleted_at')) {
                $table->softDeletes();
            }
        });
        Schema::table('celender_detail_hnhc', function (Blueprint $table) {
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('cascade')->onUpdate('cascade');
            if (! Schema::hasColumn('celender_detail_hnhc', 'deleted_at')) {
                $table->softDeletes();
            }
        });
        Schema::table('celender_detail_eatroom', function (Blueprint $table) {
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('cascade')->onUpdate('cascade');
            if (! Schema::hasColumn('celender_detail_eatroom', 'deleted_at')) {
                $table->softDeletes();
            }
        });
        Schema::table('salary_officials_vvp', function (Blueprint $table) {
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('cascade')->onUpdate('cascade');
            if (! Schema::hasColumn('salary_officials_vvp', 'deleted_at')) {
                $table->softDeletes();
            }
        });
        Schema::table('salary_officials_a7a', function (Blueprint $table) {
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('cascade')->onUpdate('cascade');
            if (! Schema::hasColumn('salary_officials_a7a', 'deleted_at')) {
                $table->softDeletes();
            }
        });
        Schema::table('schedule_details', function (Blueprint $table) {
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('cascade')->onUpdate('cascade');
            if (! Schema::hasColumn('schedule_details', 'deleted_at')) {
                $table->softDeletes();
            }
        });
        Schema::table('dailyquantities', function (Blueprint $table) {
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('cascade')->onUpdate('cascade');
            if (! Schema::hasColumn('dailyquantities', 'deleted_at')) {
                $table->softDeletes();
            }
        });
        Schema::table('check_employees', function (Blueprint $table) {
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('cascade')->onUpdate('cascade');
            if (! Schema::hasColumn('check_employees', 'deleted_at')) {
                $table->softDeletes();
            }
        });
        Schema::table('send_stamps', function (Blueprint $table) {
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('manager_id')
                ->references('id')->on('employees')
                ->onDelete('cascade')->onUpdate('cascade');
            if (! Schema::hasColumn('send_stamps', 'deleted_at')) {
                $table->softDeletes();
            }
        });
        Schema::table('storage_product', function (Blueprint $table) {
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('cascade')->onUpdate('cascade');
            if (! Schema::hasColumn('storage_product', 'deleted_at')) {
                $table->softDeletes();
            }
        });
        Schema::table('dailyquantities_po', function (Blueprint $table) {
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('cascade')->onUpdate('cascade');
            if (! Schema::hasColumn('dailyquantities_po', 'deleted_at')) {
                $table->softDeletes();
            }
        });
        Schema::table('login_history', function (Blueprint $table) {
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('cascade')->onUpdate('cascade');
            if (! Schema::hasColumn('login_history', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('login_history', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('dailyquantities_po', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('storage_product', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('send_stamps', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['manager_id']);
        });
        Schema::table('check_employees', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('dailyquantities', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('schedule_details', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('salary_officials_a7a', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('salary_officials_vvp', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('celender_detail_eatroom', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('celender_detail_hnhc', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('celender_detail_wc', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('celender_detail_wc_clean_men', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('celender_detail_wc_clean_women', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
    }
};
