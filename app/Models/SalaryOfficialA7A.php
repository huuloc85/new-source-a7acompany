<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryOfficialA7A extends Model
{
    use HasFactory;

    //table
    protected $table = "salary_officials_a7a";

    //paginate
    public const paginate = 100;

    protected $fillable  = [
        ///danh muc
        'salaries_manager_id',
        'employee_id',
        'salary_day',                                   // Lương Ngày
        'salary_night',                                 // Lương Đêm
        'probationary_salary_basic_26days',             // Lương CB thử việc / 26 ngày
        'probationary_salary_basic_hours',              // Lương CB thử việc / 1 giờ
        'probationary_salary_basic_extra_hours',        // Lương CB thử việc tăng ca / 1 giờ
        'allowance_apprentice',                         // phụ cấp học việc
        'salary_basic',                                 // Lương CB chính thức/ 26 ngày 
        'regular_salary_hour',                          // Lương CB/ giờ 
        'salary_overtime',                              // Lương tăng ca/giờ
        'allowance_diligence',                          // Chuyên cần 
        'allowance_responsibility',                     // Trách nhiệm 
        'allowance_overtime',                           // Phụ cấp tăng ca/ ngày 
        'allowance_night',                              // Phụ cấp đêm 
        'allowance_rice',                               // Phụ cấp cơm trưa 
        'company_insurance',                            // BHXH công ty đóng
        'insurance',                                    // BHXH người lao động đóng

        ///chi tiết
        'number_of_work_days_trial',                    // Số công ngày (thử việc)
        'day_shift_salary_trial',                       // Lương ca ngày (thử việc)
        'day_shift_salary_trial_notice',                // Lương ca ngày (thử việc) Ghi Chú
        'number_of_work_nights_trial',                  // Số công đêm (thử việc)
        'night_shift_salary_trial',                     // Lương ca đêm (thử việc)
        'night_shift_salary_trial_notice',              // Lương ca đêm (thử việc) Ghi Chú
        'overtime_hours_trial',                         // Số giờ tăng ca ( thử việc)
        'overtime_salary_trial',                        // Lương tăng ca (thử việc)
        'overtime_salary_trial_notice',                 // Lương tăng ca (thử việc) Ghi Chú
        'number_of_work',                               // Số Công
        'allowance_apprentice_detail',                  //phụ cấp học việc detail
        'allowance_apprentice_detail_notice',           //phụ cấp học việc detail Ghi Chú
        'core_hours',                                   //số giờ chính
        'official_salary',                              //lương chính thức
        'official_salary_notice',                       //lương chính thức Ghi Chú
        'allowance_diligence_detail',                   //chuyên cần detail
        'allowance_diligence_detail_notice',            //chuyên cần detail Ghi Chú
        'number_of_jobs',                               //Số công làm
        'allowance_responsibility_detail',              //trách nhiệm detail
        'allowance_responsibility_detail_notice',       //trách nhiệm detail Ghi Chú
        'overtime_hours_detail',                        //số giờ tăng ca
        'overtime_salary',                              //lương tăng ca
        'overtime_salary_notice',                       //lương tăng ca Ghi Chú
        'number_of_work_days',                          // Số công ngày 
        'allowance_rice_detail',                        //phụ cấp cơm ca ngày
        'allowance_rice_detail_notice',                 //phụ cấp cơm ca ngày Ghi Chú
        'number_of_work_nights',                        // Số công đêm
        'allowance_shift_night',                        //phụ cấp ca đêm
        'allowance_shift_night_notice',                 //phụ cấp ca đêm Ghi Chú
        'overtime_day_count_detail',                    //số ngày tăng ca
        'allowance_overtime_detail',                    //phụ cấp tăng ca
        'allowance_overtime_detail_notice',             //phụ cấp tăng ca Ghi Chú
        'holidays_count_detail',                        //số ngày lễ tết
        'holidays_money',                               //tiền lễ tết
        'holidays_money_notice',                        //tiền lễ tết Ghi Chú
        'paid_holidays_count_detail',                   //phép năm
        'paid_holidays_money',                          //tiền phép năm
        'paid_holidays_money_notice',                   //số tiền phép năm Ghi Chú
        'business_travel_hours',                        //Số giờ đi công tác
        'business_travel_unit_price_hour',              //Đơn giá đi công tác/ giờ
        'gcn_business_travel_salary',                   //Lương đi công tác GCN
        'gcn_business_travel_salary_notice',            //Lương đi công tác GCN Ghi Chú
        'number_of_business_trips',                     //Số lần đi công tác
        'business_fuel_unit_price_day',                 //Đơn giá xăng công tác/ ngày
        'allowance_gcn_business_fuel',                  //Phụ cấp xăng đi GCN
        'allowance_gcn_business_fuel_notice',           //Phụ cấp xăng đi GCN Ghi Chú
        'money_referral_people',                        // Tiền giới thiệu người
        'money_referral_people_notice',                 // Tiền giới thiệu người Ghi Chú
        'allowance_diffrent',                           //phụ cấp khác
        'allowance_diffrent_notice',                    //phụ cấp khác Ghi Chú
        'bonuses_for_attendance',                       // Tiền thưởng đạt chuyên cần
        'bonuses_for_attendance_notice',                // Tiền thưởng đạt chuyên cần Ghi Chú
        'sickness',                                     //Ốm đau
        'sickness_notice',                              //Ốm đau Ghi Chú
        'funeral',                                      //Ma chay
        'funeral_notice',                               //Ma chay Ghi Chú
        'birthday_money',                               //Tiền sinh nhật 
        'birthday_money_notice',                        //Tiền sinh nhật Ghi Chú
        'previous_period_debt',                         //Tiền lương tháng trước bị thiếu 
        'previous_period_debt_notice',                  //Tiền lương tháng trước bị thiếu Ghi Chú
        'total_income',                                 //Tổng thu nhập 
        'insurance_detail',                             //Khấu trừ BHXH 10.5% 
        'insurance_detail_notice',                      //Khấu trừ BHXH 10.5% Ghi Chú
        'advance_money',                                //tạm ứng
        'advance_money_notice',                         //tạm ứng Ghi Chú
        'number_of_violations',                         //Số lần vi phạm
        'subtract_of_violations',                       //Trừ vi phạm
        'subtract_of_violations_notice',                //Trừ vi phạm Ghi Chú
        'daysleave_allowed',                            //số ngày nghỉ có phép
        'subtract_daysleave_allowed',                   //Trừ tiền nghỉ có phép
        'subtract_daysleave_allowed_notice',            //Trừ tiền nghỉ có phép Ghi Chú
        'daysleave_notallowed',                         //số ngày nghĩ không phép
        'subtract_daysleave_notallowed',                //Trừ tiền nghỉ không phép
        'subtract_daysleave_notallowed_notice',         //Trừ tiền nghỉ không phép Ghi Chú
        'error_serious',                                //số lỗi nặng
        'subtract_error_serious',                       //Trừ tiền số lỗi nặng
        'subtract_error_serious_notice',                //Trừ tiền số lỗi nặng Ghi Chú
        'error_minor',                                  //số lỗi nhẹ
        'subtract_error_minor',                         //Trừ tiền số lỗi nhẹ
        'subtract_error_minor_notice',                  //Trừ tiền số lỗi nhẹ Ghi Chú
        'kpi_subtraction',                              //trừ KPI
        'kpi_subtraction_notice',                       //trừ KPI Ghi Chú
        'actually_received',                            //thực lãnh 
        'forms_of_payment',                             //hình thức thanh toán
        'company_insurance_detail',                     //BHXH (21.5%) công ty đóng cho NLĐ

        ///chấm công
        'total_day_offical',                             //tổng ngày
        'total_night_offical',                           //tổng đêm
        'total_overtime_offical',                        //tổng tăng ca
        'workday_count_trail',                           //số công ngày
        'worknight_count_trial',                         //số công đêm
        'overtime_day_count_trial',                      //số ngày tăng ca
        'allowance_rice_day_timekeeping',                //Phụ cấp tiền cơm ngày
        'allowance_rice_night_timekeeping',              //Phụ cấp tiền cơm đêm
        'allowance_overtime_timekeeping',                //phụ cấp tăng ca
        'holidays_count',                                //số ngày nghĩ lễ tết
        'paid_holidays_count',                           //số ngày phép năm
        'daysleave_allowed_timekeeping',                 //số ngày nghỉ có phép
        'daysleave_notallowed_timekeeping',              //số ngày nghỉ không phép

        //bảng lương
        'salary_total',                                 //tổng lương
        'insurance_payroll',                            //trừ bảo hiểm
        'advance_money_payroll',                        //tạm ứng
        'company_insurance_payroll',                    //bảo hiểm công ty đóng
        'KPI_Subtraction_payroll',                      //trừ KPI
        'previous_period_debt_payroll',                 //Nợ kỳ trước
        'actually_received_payroll',                    //thực lãnh detail
    ];

    //relationship SalaryManager
    public function SalaryManager()
    {
        return $this->belongsTo(SalaryManager::class, 'salaries_manager_id', 'id');
    }

    //relationship SalaryOfficialTimekepings
    public function SalaryOfficialA7ATimekeepings()
    {
        return $this->hasMany(SalaryOfficialA7ATimekeeping::class, 'salary_official_a7a_id', 'id');
    }

    //relationship employee
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
