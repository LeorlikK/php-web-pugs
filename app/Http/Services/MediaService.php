<?php

namespace App\Http\Services;

use Database\DB;

class MediaService
{
    public static function generateUrl(array $file, $way, $table, $field):string
    {
        $type = StrService::stringFilter($file['type']);
        $extension = explode('/', $type)[1];
        $name = '';
        $uniqueName = false;
        while ($uniqueName !== true){
            $dateName = md5(time());
            $name = $way . $dateName . ".$extension";
            if (!count(DB::select("SELECT * FROM $table WHERE $field = ?", [$name])->fetchAll()) > 0 ) {
                $uniqueName = true;
            }
        }
        return $name;
    }

    public static function createName(array $file):string
    {
        $name = StrService::stringFilter($file['name']);
        $pos = strripos($name, '.');
        return mb_substr($name, 0, $pos);
    }
}