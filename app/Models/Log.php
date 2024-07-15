<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    //paginate
    public const paginate = 10;

    //table
    protected $table = "logs";

    //fillable
    protected $fillable = [
        'table',
        'content',
        'row'

    ];
}
