<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'stock_transactions';

    // Fillable fields
    protected $fillable = [
        'storage_product_id',
        'type',
        'quantity',
        'remaining_quantity',
        'note',
        'employee_id',
    ];

    // Casts
    protected $casts = [
        'quantity' => 'integer',
        'remaining_quantity' => 'integer',
    ];

    // Relationships
    public function storageProduct()
    {
        return $this->belongsTo(StorageProduct::class, 'storage_product_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    // Scopes
    public function scopeIn($query)
    {
        return $query->where('type', 'in');
    }

    public function scopeOut($query)
    {
        return $query->where('type', 'out');
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->whereHas('storageProduct', function ($q) use ($productId) {
            $q->where('product_id', $productId);
        });
    }

    // Accessors
    public function getTypeTextAttribute()
    {
        return $this->type === 'in' ? 'Nhập kho' : 'Xuất kho';
    }
}
