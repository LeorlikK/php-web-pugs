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

    public static function generateUrlFromString(string $string, $way, $table, $field):string
    {
        $type = StrService::stringFilter($string);
//        $extension = explode('/', $type)[1];
        $name = '';
        $uniqueName = false;
        while ($uniqueName !== true){
            $dateName = md5(time());
            $name = $way . $dateName . ".png";
            if (!count(DB::select("SELECT * FROM $table WHERE $field = ?", [$name])->fetchAll()) > 0 ) {
                $uniqueName = true;
            }
        }
        return $name;
    }

    public static function createName(array $file):string
    {
        $name = StrService::stringFilter($file['name']);
        $pos = mb_strripos($name, '.');
        return mb_substr($name, 0, $pos);
    }

    public static function tmpClear():void
    {
        $oldImages = scandir('resources/images/tmp/', SCANDIR_SORT_NONE);
        $oldImages = array_slice($oldImages, 2);
        foreach ($oldImages as $oldImage){
            unlink('resources/images/tmp/' . $oldImage);
        }
    }

    public static function deletePhoto($route):bool
    {
        if (file_exists($route)){
            if ($route !== 'resources/images/avatar/avatar_default.png'){
                unlink($route);
                return true;
            }
        }
        return false;
    }
}