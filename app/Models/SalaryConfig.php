<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryConfig extends Model
{
    use HasFactory;

    protected $table = 'salary_configs';

    protected $fillable = [
        'company',
        'company_name',
        'max_work_days_worker',
        'max_work_days_office',
        'standard_work_days',
        'hours_per_day',
        'hourly_divisor',
        'overtime_multiplier',
        'insurance_company_rate',
        'insurance_employee_rate',
        'union_fee_rate',
        'rounding_unit',
    ];

    protected $casts = [
        'overtime_multiplier' => 'float',
        'insurance_company_rate' => 'float',
        'insurance_employee_rate' => 'float',
        'union_fee_rate' => 'float',
    ];

    /**
     * Lấy số giờ tối đa cho công nhân
     */
    public function getMaxHoursWorkerAttribute(): float
    {
        return $this->max_work_days_worker * $this->hours_per_day;
    }

    /**
     * Lấy số giờ tối đa cho văn phòng
     */
    public function getMaxHoursOfficeAttribute(): float
    {
        return $this->max_work_days_office * $this->hours_per_day;
    }
}
