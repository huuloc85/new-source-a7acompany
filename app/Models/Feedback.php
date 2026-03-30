<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'employee_id',
        'type',
        'subject',
        'content',
        'image',
        'status',
        'admin_reply',
        'replied_by',
        'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    protected $appends = ['image_url'];

    /**
     * Accessor: trả về URL đầy đủ của ảnh đính kèm
     */
    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        return asset('storage/'.$this->image);
    }

    /**
     * Người gửi góp ý
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Admin đã phản hồi
     */
    public function repliedByEmployee()
    {
        return $this->belongsTo(Employee::class, 'replied_by');
    }
}
