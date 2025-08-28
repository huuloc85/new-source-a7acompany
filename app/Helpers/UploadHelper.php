<?php

namespace App\Helpers;

class UploadHelper
{
    public static function upload($file, $groupName, $path = null)
    {
        if ($file) {
            $fileName = $groupName.'_'.time().'.'.$file->getClientOriginalExtension();
            $storage = $file->storeAs('employee', $fileName, 'public');

            return $storage;
        }

        return null;
    }
}
