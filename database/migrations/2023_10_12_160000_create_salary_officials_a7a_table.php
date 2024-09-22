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
        Schema::create('salary_officials_a7a', function (Blueprint $table) {
            //danh muc
            $table->id();
            $table->foreignId('salaries_manager_id')->references('id')->on('salaries_manager');
            $table->foreignId('employee_id')->references('id')->on('employees');
            $table->double('salary_day', 19, 2)->nullable();                                // Lương Ngày
            $table->double('salary_night', 19, 2)->nullable();                              // Lương Đêm
            $table->string('probationary_salary_basic_26days', 255)->nullable();             // Lương CB thử việc / 26 ngày
            $table->string('probationary_salary_basic_hours', 255)->nullable();              // Lương CB thử việc / 1 giờ
            $table->string('probationary_salary_basic_extra_hours', 255)->nullable();        // Lương CB thử việc tăng ca / 1 giờ
            $table->double('allowance_apprentice', 19, 2)->nullable();                       // phụ cấp học việc
            $table->double('salary_basic', 19, 2)->nullable();                               // Lương CB chính thức/ 26 ngày 
            $table->double('regular_salary_hour', 19, 2)->nullable();                        // Lương CB/ giờ 
            $table->double('salary_overtime', 19, 2)->nullable();                            // Lương tăng ca/giờ
            $table->double('allowance_diligence', 19, 2)->nullable();                        // Chuyên cần 
            $table->double('allowance_responsibility', 19, 2)->nullable();                   // Trách nhiệm 
            $table->double('allowance_overtime', 19, 2)->nullable();                         // Phụ cấp tăng ca/ ngày 
            $table->double('allowance_night', 19, 2)->nullable();                            // Phụ cấp đêm 
            $table->double('allowance_rice', 19, 2)->nullable();                             // Phụ cấp cơm trưa 
            $table->double('company_insurance', 19, 2)->nullable();                          // BHXH công ty đóng
            $table->double('insurance', 19, 2)->nullable();

            //chấm công
            $table->double('total_day_offical', 19, 2)->nullable();                    //tổng ngày
            $table->double('total_night_offical', 19, 2)->nullable();                  //tổng đêm
            $table->double('total_overtime_offical', 19, 2)->nullable();               //tổng tăng ca
            $table->double('workday_count_trial', 19, 2)->nullable();                  //số công ngày
            $table->double('worknight_count_trial', 19, 2)->nullable();                //số công đêm
            $table->double('overtime_day_count_trial', 19, 2)->nullable();             //số ngày tăng ca
            $table->double('allowance_rice_day_timekeeping', 19, 2)->nullable();       //Phụ cấp tiền cơm ngày
            $table->double('allowance_rice_night_timekeeping', 19, 2)->nullable();     //Phụ cấp tiền cơm đêm
            $table->double('allowance_overtime_timekeeping', 19, 2)->nullable();       //phụ cấp tăng ca
            $table->double('holidays_count', 19, 2)->nullable();                       //số ngày nghĩ lễ tết
            $table->double('paid_holidays_count', 19, 2)->nullable();                  //số ngày phép năm
            $table->double('daysleave_allowed_timekeeping', 19, 2)->nullable();        //số ngày nghỉ có phép
            $table->double('daysleave_notallowed_timekeeping', 19, 2)->nullable();     //số ngày nghỉ không phép


            //chi tiết
            $table->integer('number_of_work_days_trial')->nullable();                       // Số công ngày (thử việc)
            $table->double('day_shift_salary_trial', 19, 2)->nullable();                    // Lương ca ngày (thử việc)
            $table->string('day_shift_salary_trial_notice', 255)->nullable();               // Lương ca ngày (thử việc) Ghi Chú
            $table->integer('number_of_work_nights_trial')->nullable();                      // Số công đêm (thử việc)
            $table->double('night_shift_salary_trial', 19, 2)->nullable();                   // Lương ca đêm (thử việc)
            $table->string('night_shift_salary_trial_notice', 255)->nullable();              // Lương ca đêm (thử việc) Ghi Chú
            $table->double('overtime_hours_trial', 19, 2)->nullable();                       // Số giờ tăng ca ( thử việc)
            $table->double('overtime_salary_trial', 19, 2)->nullable();                      // Lương tăng ca (thử việc)
            $table->string('overtime_salary_trial_notice', 255)->nullable();                 // Lương tăng ca (thử việc) Ghi Chú
            $table->integer('number_of_work')->nullable();                                   // Số Công
            $table->double('allowance_apprentice_detail', 19, 2)->nullable();                // Phụ cấp học việc detail
            $table->string('allowance_apprentice_detail_notice', 255)->nullable();           // Phụ cấp học việc detail Ghi Chú
            $table->double('core_hours')->nullable();                                        // Số giờ chính
            $table->double('official_salary', 19, 2)->nullable();                             // Lương chính thức
            $table->string('official_salary_notice', 255)->nullable();                        // Lương chính thức Ghi Chú
            $table->double('number_of_hours_worked', 19, 2)->nullable();                      // Số công làm
            $table->double('allowance_diligence_detail', 19, 2)->nullable();                  // Chuyên cần detail
            $table->string('allowance_diligence_detail_notice', 255)->nullable();             // Chuyên cần detail Ghi Chú
            $table->integer('number_of_jobs')->nullable();                                    // Số công làm
            $table->double('allowance_responsibility_detail', 19, 2)->nullable();              // Trách nhiệm detail
            $table->string('allowance_responsibility_detail_notice', 255)->nullable();         // Trách nhiệm detail Ghi Chú
            $table->double('overtime_hours_detail', 19, 2)->nullable();                        // Số giờ tăng ca
            $table->double('overtime_salary', 19, 2)->nullable();                              // Lương tăng ca
            $table->string('overtime_salary_notice', 255)->nullable();                         // Lương tăng ca Ghi Chú
            $table->integer('number_of_work_days')->nullable();                                // Số công ngày 
            $table->double('allowance_rice_detail', 19, 2)->nullable();                        // Phụ cấp cơm ca ngày
            $table->string('allowance_rice_detail_notice', 255)->nullable();                    // Phụ cấp cơm ca ngày Ghi Chú
            $table->integer('number_of_work_nights')->nullable();                               // Số công đêm
            $table->double('allowance_shift_night', 19, 2)->nullable();                         // Phụ cấp ca đêm
            $table->string('allowance_shift_night_notice', 255)->nullable();                     // Phụ cấp ca đêm Ghi Chú
            $table->double('overtime_day_count_detail', 19, 2)->nullable();                      // Số ngày tăng ca
            $table->double('allowance_overtime_detail', 19, 2)->nullable();                      // Phụ cấp tăng ca
            $table->string('allowance_overtime_detail_notice', 255)->nullable();                  // Phụ cấp tăng ca Ghi Chú
            $table->double('holidays_count_detail', 19, 2)->nullable();                            // Số ngày lễ tết
            $table->double('holidays_money', 19, 2)->nullable();                                   // Tiền lễ tết
            $table->string('holidays_money_notice', 255)->nullable();                               // Tiền lễ tết Ghi Chú
            $table->double('paid_holidays_count_detail', 19, 2)->nullable();                       // Phép năm
            $table->double('paid_holidays_money', 19, 2)->nullable();                               // Tiền phép năm
            $table->string('paid_holidays_money_notice', 255)->nullable();                           // Số tiền phép năm Ghi Chú
            $table->double('business_travel_hours', 19, 2)->nullable();                              // Số giờ đi công tác
            $table->double('business_travel_unit_price_hour', 19, 2)->nullable();                    // Đơn giá đi công tác/ giờ
            $table->double('gcn_business_travel_salary', 19, 2)->nullable();                           // Lương đi công tác GCN
            $table->string('gcn_business_travel_salary_notice', 255)->nullable();                       // Lương đi công tác GCN Ghi Chú
            $table->integer('number_of_business_trips')->nullable();                                    // Số lần đi công tác
            $table->double('business_fuel_unit_price_day', 19, 2)->nullable();                          // Đơn giá xăng công tác/ ngày
            $table->double('allowance_gcn_business_fuel', 19, 2)->nullable();                           // Phụ cấp xăng đi GCN
            $table->string('allowance_gcn_business_fuel_notice', 255)->nullable();                       // Phụ cấp xăng đi GCN Ghi Chú
            $table->double('money_referral_people', 19, 2)->nullable();                                 // Tiền giới thiệu người
            $table->string('money_referral_people_notice', 255)->nullable();                             // Tiền giới thiệu người Ghi Chú
            $table->double('allowance_diffrent', 19, 2)->nullable();                                      // Phụ cấp khác
            $table->string('allowance_diffrent_notice', 255)->nullable();                                  // Phụ cấp khác Ghi Chú
            $table->double('bonuses_for_attendance', 19, 2)->nullable();                                   // Tiền thưởng đạt chuyên cần
            $table->string('bonuses_for_attendance_notice', 255)->nullable();                               // Tiền thưởng đạt chuyên cần Ghi Chú
            $table->double('sickness', 19, 2)->nullable();                                                    // Ốm đau
            $table->string('sickness_notice', 255)->nullable();                                                // Ốm đau Ghi Chú
            $table->double('funeral', 19, 2)->nullable();                                                     // Ma chay
            $table->string('funeral_notice', 255)->nullable();                                                 // Ma chay Ghi Chú
            $table->double('birthday_money', 19, 2)->nullable();                                               // Tiền sinh nhật 
            $table->string('birthday_money_notice', 255)->nullable();                                            // Tiền sinh nhật Ghi Chú
            $table->double('previous_period_debt', 19, 2)->nullable();                                           // Tiền lương tháng trước bị thiếu 
            $table->string('previous_period_debt_notice', 255)->nullable();                                       // Tiền lương tháng trước bị thiếu Ghi Chú
            $table->double('total_income', 19, 2)->nullable();                                                    // Tổng thu nhập 
            $table->double('insurance_detail', 19, 2)->nullable();                                                 // Khấu trừ BHXH 10.5% 
            $table->string('insurance_detail_notice', 255)->nullable();                                             // Khấu trừ BHXH 10.5% Ghi Chú
            $table->double('advance_money', 19, 2)->nullable();                                                      // Tạm ứng
            $table->string('advance_money_notice', 255)->nullable();                                                   // Tạm ứng Ghi Chú
            $table->double('number_of_violations')->nullable();                                                        // Số lần vi phạm
            $table->double('subtract_of_violations', 19, 2)->nullable();                                                  // Trừ vi phạm
            $table->string('subtract_of_violations_notice', 255)->nullable();                                               // Trừ vi phạm Ghi Chú
            $table->double('daysleave_allowed', 19, 2)->nullable();                                                         // Số ngày nghỉ có phép
            $table->double('subtract_daysleave_allowed', 19, 2)->nullable();                                                  // Trừ tiền nghỉ có phép
            $table->string('subtract_daysleave_allowed_notice', 255)->nullable();                                               // Trừ tiền nghỉ có phép Ghi Chú
            $table->double('daysleave_notallowed', 19, 2)->nullable();                                                             // Số ngày nghĩ không phép
            $table->double('subtract_daysleave_notallowed', 19, 2)->nullable();                                                      // Trừ tiền nghỉ không phép
            $table->string('subtract_daysleave_notallowed_notice', 255)->nullable();                                                   // Trừ tiền nghỉ không phép Ghi Chú
            $table->double('error_serious', 19, 2)->nullable();                                                                           // Số lỗi nặng
            $table->double('subtract_error_serious', 19, 2)->nullable();                                                                    // Trừ tiền số lỗi nặng
            $table->string('subtract_error_serious_notice', 255)->nullable();                                                                   // Trừ tiền số lỗi nặng Ghi Chú
            $table->double('error_minor', 19, 2)->nullable();                                                                                    // Số lỗi nhẹ
            $table->double('subtract_error_minor', 19, 2)->nullable();                                                                               // Trừ tiền số lỗi nhẹ
            $table->string('subtract_error_minor_notice', 255)->nullable();                                                                            // Trừ tiền số lỗi nhẹ Ghi Chú
            $table->double('kpi_subtraction', 19, 2)->nullable();                                                                                         // Trừ KPI
            $table->string('kpi_subtraction_notice', 255)->nullable();                                                                                      // Trừ KPI Ghi Chú
            $table->double('actually_received', 19, 2)->nullable();                                                                                            // Thực lãnh 
            $table->string('forms_of_payment', 255)->nullable();                                                                                                 // Hình thức thanh toán
            $table->double('company_insurance_detail', 19, 2)->nullable();

            //bảng lương
            $table->integer('salary_total')->nullable();                              //tổng lương
            $table->double('insurance_payroll', 19, 2)->nullable();                   //trừ bảo hiểm
            $table->double('advance_money_payroll')->nullable();                      //tạm ứng
            $table->double('company_insurance_payroll', 19, 2)->nullable();           //bảo hiểm công ty đóng
            $table->double('KPI_Subtraction_payroll', 19, 2)->nullable();             //trừ KPI
            $table->double('previous_period_debt_payroll', 19, 2)->nullable();        //Nợ kỳ trước
            $table->double('actually_received_payroll', 19, 2)->nullable();           //thực lãnh detail
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_officials_a7a');
    }
};
