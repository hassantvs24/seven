<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('db_result')) {
    function db_result($data)
    {
        return ['message' => 'Data Successfully Find', 'data' => $data];
    }
}

if (!function_exists('checkNull')) {
    function checkNull($val)
    {
        return ($val === 'null' ? '' : $val);
    }
}

if (!function_exists('validate_error')) {
    function validate_error($error)
    {
        return ['message' => $error];
    }
}

if (!function_exists('del_file')) {

    function del_file($path, $disk)
    {

        $exists = Storage::disk($disk)->exists($path);
        if ($exists) {
            Storage::disk($disk)->delete($path);
        }

        return true;
    }
}


if (!function_exists('validate_error')) {
    function validate_error($error)
    {
        return ['message' => $error];
    }
}