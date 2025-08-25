<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';

    protected $fillable = [
        'title',
        'path',
        'employee_id',
        'type',
        'is_show',
        'description',
        'size',
        'extension',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function getImageUrlAttribute()
    {
        return asset($this->path);
    }

    public function getFormattedSizeAttribute()
    {
        return number_format($this->size / 1024, 2).' KB';
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = trim($value);
    }

    public function setPathAttribute($value)
    {
        $this->attributes['path'] = trim($value);
    }

    public function setEmployeeIdAttribute($value)
    {
        $this->attributes['employee_id'] = $value;
    }

    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = trim($value);
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = trim($value);
    }

    public function setSizeAttribute($value)
    {
        $this->attributes['size'] = $value;
    }

    public function setExtensionAttribute($value)
    {
        $this->attributes['extension'] = trim($value);
    }
}
