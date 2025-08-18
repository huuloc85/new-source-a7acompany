<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageProduct extends Model
{
    use HasFactory;

    // paginate
    public const paginate = 10;

    // table
    protected $table = 'storage_product';

    // fillable
    protected $fillable = [
        'product_id',
        'lot',
        'employee_id',
        'bin',
    ];

    // relationship product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
