<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salary_configs', function (Blueprint $table) {
            $table->id();
            $table->string('company', 10)->unique();                        // 'a7a', 'vvp'
            $table->string('company_name', 255)->nullable();                // Tên công ty đầy đủ
            $table->integer('max_work_days_worker')->default(25);           // Số ngày làm tối đa (công nhân)
            $table->integer('max_work_days_office')->default(25);           // Số ngày làm tối đa (văn phòng)
            $table->integer('standard_work_days')->default(26);             // Số ngày chuẩn/tháng
            $table->integer('hours_per_day')->default(8);                   // Số giờ/ngày
            $table->integer('hourly_divisor')->default(204);                // Chia để tính lương giờ (N/204)
            $table->decimal('overtime_multiplier', 3, 2)->default(1.50);    // Hệ số tăng ca
            $table->decimal('insurance_company_rate', 5, 4)->default(0.2150); // BHXH CTY đóng 21.5%
            $table->decimal('insurance_employee_rate', 5, 4)->default(0.1050); // BHXH NLĐ đóng 10.5%
            $table->decimal('union_fee_rate', 5, 4)->default(0.0050);       // Phí công đoàn 0.5%
            $table->integer('rounding_unit')->default(1000);                // Đơn vị làm tròn (FLOOR)
            $table->timestamps();
        });

        // Seed dữ liệu mặc định cho A7A và VVP
        DB::table('salary_configs')->insert([
            [
                'company' => 'a7a',
                'company_name' => 'CÔNG TY TNHH MTV A7A',
                'max_work_days_worker' => 25,
                'max_work_days_office' => 25,
                'standard_work_days' => 26,
                'hours_per_day' => 8,
                'hourly_divisor' => 204,
                'overtime_multiplier' => 1.50,
                'insurance_company_rate' => 0.2150,
                'insurance_employee_rate' => 0.1050,
                'union_fee_rate' => 0.0050,
                'rounding_unit' => 1000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company' => 'vvp',
                'company_name' => 'CÔNG TY TNHH MTV VINH VINH PHÁT',
                'max_work_days_worker' => 19,
                'max_work_days_office' => 19,  // VVP: 18.5 cho cán bộ, sẽ xử lý riêng
                'standard_work_days' => 26,
                'hours_per_day' => 8,
                'hourly_divisor' => 204,
                'overtime_multiplier' => 1.50,
                'insurance_company_rate' => 0.2150,
                'insurance_employee_rate' => 0.1050,
                'union_fee_rate' => 0.0050,
                'rounding_unit' => 1000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_configs');
    }
};
