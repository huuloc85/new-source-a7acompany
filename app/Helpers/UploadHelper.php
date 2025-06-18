<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class UploadHelper
{
    public static function upload($file, $groupName, $path = null)
    {
        if ($file) {
            $fileName = $groupName.'_'.time().'.'.$file->getClientOriginalExtension();
            $storage = Storage::putFileAs('public/'.($path ?: 'employee'), $file, $fileName);

            return Storage::url($storage);
        }

        return null;
    }
}
