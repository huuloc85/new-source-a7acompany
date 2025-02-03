<?php

namespace App\Helpers;

use App\Models\Log;

class LogHelper
{
    public static function saveLog($table, $content, $row)
    {
        Log::create([
            'table' => $table,
            'content' => $content,
            'row' => $row,
        ]);
    }
}
