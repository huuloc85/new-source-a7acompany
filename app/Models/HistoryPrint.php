<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPrint extends Model
{
    use HasFactory;

    //paginate
    public const paginate = 10;

    protected $table = "history_prints";

    protected $fillable = [
        'product_id',
        'employee_id',
        'date',
        'shift',
        'binCount',
        'binStart'
    ];

    //relationship employees
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    //relationship employees
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
