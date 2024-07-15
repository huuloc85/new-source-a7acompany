<?php

namespace App\Helpers;

use App\Models\Log;
use App\Models\LoginHistory;
use Carbon\Carbon;

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
