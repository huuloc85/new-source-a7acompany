<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckEmployee extends Model
{
    use HasFactory;

    protected $table = "check_employees";

    protected $fillable = [
        'product_id',
        'employee_id',
        'date',
        'shift',
        'timestamp',
    ];

    // Định nghĩa quan hệ với model Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Định nghĩa quan hệ với model Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
