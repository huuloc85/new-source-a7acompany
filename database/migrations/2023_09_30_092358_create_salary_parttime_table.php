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
        Schema::create('salary_parttimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salaries_manager_id')->references('id')->on('salaries_manager');
            $table->foreignId('employee_id')->references('id')->on('employees');

            //bang thanh toan luong
            $table->double('salary_total', 19, 2)->nullable();                      //tổng lương
            $table->double('insurance', 19, 2)->nullable();                         //trừ bảo hiểm
            $table->double('advance_money', 19, 2)->nullable();                     //tạm ứng
            $table->double('company_insurance', 19, 2)->nullable();                 //bảo hiểm công ty phải đóng
            $table->double('debt_last', 19, 2)->nullable();                         //nợ kỳ trước
            $table->double('actually_received')->nullable();                        //thực lãnh

            //luong ngay lanh tuan
            $table->double('total_day', 19, 2)->nullable();                           //tổng ngày
            $table->double('total_night', 19, 2)->nullable();                         //tổng đêm
            $table->double('total_overtime', 19, 2)->nullable();                      //tổng tăng ca
            $table->double('workday_money', 19, 2)->nullable();                       //số tiền ngày
            $table->double('worknight_money', 19, 2)->nullable();                     //số tiền đêm
            $table->double('allowance_outwork', 19, 2)->nullable();                   //phụ cấp hết việc
            $table->double('salary_total_2', 19, 2)->nullable();                      //phụ cấp hết việc

            $table->double('holidays_count', 19, 2)->nullable();                      //số ngày nghĩ lễ tết
            $table->double('paid_holidays_count', 19, 2)->nullable();                 //số ngày phép năm
            $table->double('outwork_day_count', 19, 2)->nullable();                   //số ngày hết việc
            $table->double('increase_day', 19, 2)->nullable();                        //tăng cường ngày
            $table->double('increase_night', 19, 2)->nullable();                      //tăng cường đêm
            $table->double('annual_leave', 19, 2)->nullable();                        //phép năm lũy kế thừa tháng này
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_parttimes');
    }
};
