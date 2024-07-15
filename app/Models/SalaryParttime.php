<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryParttime extends Model
{
    use HasFactory;

    //table
    protected $table = "salary_parttimes";

    //paginate
    public const paginate = 10;

    protected $fillable  = [
        'salaries_manager_id',
        'employee_id',
        
        //bang thanh toan luong
        'salary_total',         //tổng lương
        'insurance',            //trừ bảo hiểm
        'advance_money',        //tạm ứng
        'company_insurance',    //bảo hiểm công ty phải đóng
        'debt_last',            //nợ kỳ trước
        'actually_received',    //thực lãnh

        //luong ngay lanh tuan
        'total_day',            //tổng ngày
        'total_night',          //tổng đêm
        'total_overtime',       //tổng tăng ca
        'workday_money',        //số tiền ngày
        'worknight_money',      //số tiền đêm
        'allowance_outwork',    //phụ cấp hết việc
        'salary_total_2',       //tổng lương 2

        'holidays_count',       //số ngày nghĩ lễ tết
        'paid_holidays_count',  //số ngày phép năm
        'outwork_day_count',    //số ngày hết việc
        'increase_day',         //tăng cường ngày
        'increase_night',       //tăng cường đêm
        'annual_leave',         //phép năm lũy kế thừa tháng này
    ];

    //relationship SalaryManager
    public function SalaryManager()
    {
        return $this->belongsTo(SalaryManager::class, 'salaries_manager_id', 'id');
    }

    //relationship SalaryParttimeTimekeepings
    public function SalaryParttimeTimekeepings()
    {
        return $this->hasMany(SalaryParttimeTimekeeping::class, 'salary_parttime_id', 'id');
    }

    //relationship employee
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
