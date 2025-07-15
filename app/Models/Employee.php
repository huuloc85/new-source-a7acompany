<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    // paginate
    public const paginate = 50;

    // table
    protected $table = 'employees';

    // primary key
    protected $primaryKey = 'id';

    // key type
    protected $keyType = 'string';

    // fillable
    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'address',
        'home_town',
        'gender',
        'birthday',
        'CCCD',
        'photo',
        'card_photo',
        'marital_status',
        'date_joining',
        'role_id',
        'company',
        'calendar_category_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getEmployeeTotalHoursAttribute()
    {
        return $this->employeeTotalHours;
    }

    // Setter for employeeTotalHours
    public function setEmployeeTotalHoursAttribute($value)
    {
        $this->employeeTotalHours = $value;
    }

    public function getDataAttribute()
    {
        return $this->dataAttendance;
    }

    public function setDataAttribute($value)
    {
        $this->dataAttendance = $value;
    }

    public function getEmployeeForPCAttribute()
    {
        return $this->employeeforPC;
    }

    // Setter cho employeeforPC
    public function setEmployeeForPCAttribute($value)
    {
        $this->employeeforPC = $value;
    }

    // relationship role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // relationship salaryOfficial
    public function SalaryOfficialsA7A()
    {
        return $this->hasMany(SalaryOfficialA7A::class, 'employee_id', 'id');
    }

    public function SalaryOfficialsVVP()
    {
        return $this->hasMany(SalaryOfficialVVP::class, 'employee_id', 'id');
    }

    // relationship celenderDetails
    public function celenderDetailsHNHC()
    {
        return $this->hasMany(CelenderDetailHNHC::class, 'employee_id', 'id');
    }

    // relationship celenderDetails
    public function celenderDetailsEatroom()
    {
        return $this->hasMany(CelenderDetailEatroom::class, 'employee_id', 'id');
    }

    // relationship celenderDetails
    public function celenderDetailsWC()
    {
        return $this->hasMany(CelenderDetailWC::class, 'employee_id', 'id');
    }

    public function celenderDetailsWCCleanWomen()
    {
        return $this->hasMany(CelenderDetailWCCleanMen::class, 'employee_id', 'id');
    }

    public function celenderDetailsWCCleanMen()
    {
        return $this->hasMany(CelenderDetailWCCleanMen::class, 'employee_id', 'id');
    }

    // relationship DailyQuantity
    public function DailyQuantities()
    {
        return $this->hasMany(DailyQuantity::class, 'employee_id', 'id');
    }

    public function DailyQuantitiesPO()
    {
        return $this->hasMany(DailyQuantityPO::class, 'employee_id', 'id');
    }

    public function checkEmployees()
    {
        return $this->hasMany(CheckEmployee::class, 'employee_id', 'id');
    }

    public function sendStamps()
    {
        return $this->hasMany(SendStamp::class, 'employee_id', 'id');
    }

    // search by role
    public function scopeNameRole($query, $request)
    {
        if ($request->has('role_id')) {
            return $query->whereHas('role', function ($query) use ($request) {
                $query->where('role_id', $request->role_id);
            });
        }
    }

    // search by role
    public function scopeNameCate($query, $request)
    {
        if ($request->has('category_celender_id')) {
            return $query->whereHas('category_celender', function ($query) use ($request) {
                $query->where('category_celender_id', $request->category_celender_id);
            });
        }
    }

    // search by name
    public function scopeName($query, $request)
    {
        if ($request->has('name')) {
            return $query->where('name', 'like', '%'.$request->name.'%');
        }

        return $query;
    }

    // search by address
    public function scopeAddress($query, $request)
    {
        if ($request->has('address')) {
            return $query->where('address', 'like', '%'.$request->address.'%');
        }

        return $query;
    }

    // search by home town
    public function scopeHomeTown($query, $request)
    {
        if ($request->has('home_town')) {
            return $query->where('home_town', 'like', '%'.$request->home_town.'%');
        }

        return $query;
    }

    // search by phone
    public function scopePhone($query, $request)
    {
        if ($request->has('phone')) {
            return $query->where('phone', 'like', '%'.$request->phone.'%');
        }

        return $query;
    }

    // search by code
    public function scopeCode($query, $request)
    {
        if ($request->has('code')) {
            return $query->where('code', 'like', '%'.$request->code.'%');
        }

        return $query;
    }

    // search by CCCD
    public function scopeCCCD($query, $request)
    {
        if ($request->has('CCCD')) {
            return $query->where('CCCD', 'like', '%'.$request->CCCD.'%');
        }

        return $query;
    }

    // search by gender
    public function scopeGender($query, $request)
    {
        if ($request->has('gender')) {
            $query->where('gender', $request->gender);
        }

        return $query;
    }

    // relationships 1-n
    public function calendarCategory()
    {
        return $this->belongsTo(CategoryCelender::class);
    }

    public function loginHistory()
    {
        return $this->hasMany(LoginHistory::class, 'employee_id', 'id');
    }

    public function storageProducts()
    {
        return $this->hasMany(StorageProduct::class, 'employee_id', 'id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class, 'employee_code', 'id');
    }

    public function scheduleDetails()
    {
        return $this->hasMany(ScheduleDetail::class, 'employee_id', 'id');
    }

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'schedule_details', 'employee_id', 'schedule_id')
            ->withPivot(['date', 'is_wc_clean_men', 'is_wc_clean_women', 'is_wc_trash', 'is_eat_room', 'hnhc']);
    }
}
