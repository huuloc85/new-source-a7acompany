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
        'quantity',
        'barcode',
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

    // Quan hệ với stock transactions
    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class, 'storage_product_id', 'id');
    }

    // Phương thức thêm số lượng (nhập kho)
    public function addStock($quantity, $type = 'in', $note = null, $employee_id = null)
    {
        $this->increment('quantity', $quantity);

        // Ghi lại transaction (bin system - không cần remaining_quantity)
        return $this->stockTransactions()->create([
            'type' => $type,
            'quantity' => $quantity,
            'note' => $note,
            'employee_id' => $employee_id ?? $this->employee_id,
        ]);
    }

    // Phương thức trừ số lượng (xuất kho)
    public function removeStock($quantity, $note = null, $employee_id = null)
    {
        if ($this->quantity < $quantity) {
            throw new \Exception('Không đủ số lượng trong kho. Hiện có: '.$this->quantity);
        }

        $this->decrement('quantity', $quantity);

        // Load relationships trước khi có thể bị xóa
        $this->load(['product']);

        // Ghi lại transaction (bin system - không cần remaining_quantity)
        $transaction = $this->stockTransactions()->create([
            'type' => 'out',
            'quantity' => $quantity,
            'note' => $note,
            'employee_id' => $employee_id ?? $this->employee_id,
        ]);

        // Đảm bảo quantity không âm (không xóa record nữa)
        if ($this->quantity < 0) {
            $this->quantity = 0;
            $this->save();
        }

        return $transaction;
    }

    // Tìm theo barcode
    public static function findByBarcode($barcode)
    {
        return static::where('barcode', $barcode)->first();
    }

    // Kiểm tra tồn kho
    public function hasStock($quantity = 1)
    {
        return $this->quantity >= $quantity;
    }

    // Scope để tìm sản phẩm có trong kho
    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    // Scope để tìm sản phẩm hết hàng
    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '<=', 0);
    }
}
