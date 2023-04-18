<?php

namespace App\Http\Services;

use Database\DB;

class MediaService
{
    public static function generateUniqueUrl(array $file, string $way, string $table, string $field):string
    {
        $name = '';
        $uniqueName = false;
        while ($uniqueName !== true){
            $name = self::generateUrl($file, $way);
            $uniqueName = self::forTable($name, $table, $field);
        }
        return $name;
    }

    public static function generateFolderUniqueUrl(array $file, string $way):string
    {
        $name = '';
        $uniqueName = false;
        while ($uniqueName !== true){
            $name = self::generateUrl($file, $way);
            $uniqueName = self::forFolder($name);
        }
        return $name;
    }

    public static function generateUrl(array $file, string $way):string
    {
        $extension = self::extension($file);
        $dateName = md5(time());
        return $way . $dateName . ".$extension";
    }

    public static function extension(array $file):string
    {
        $type = StrService::stringFilter($file['type']);
        return explode('/', $type)[1];
    }

    public static function generateUrlFromString(string $string, $way, $table, $field):string
    {
        $extension = '.png';
        $name = '';
        $uniqueName = false;
        while ($uniqueName !== true){
            $dateName = md5(time());
            $name = $way . $dateName . $extension;
            if (!count(DB::select("SELECT * FROM $table WHERE $field = ?", [$name])->fetchAll()) > 0 ) {
                $uniqueName = true;
            }
        }
        return $name;
    }

    private static function forTable($name, $table, $field):bool
    {
        if (!count(DB::select("SELECT * FROM $table WHERE $field = ?", [$name])->fetchAll()) > 0 ) {
            return true;
        }
        return false;
    }

    private static function forFolder($name):bool
    {
        if (!file_exists($name)){
            return true;
        }
        return false;
    }

    public static function createName(string $string):string
    {
        $name = StrService::stringFilter($string);
        $position = mb_strripos($name, '.');
        if ($position){
            return mb_substr($name, 0, $position);
        }else{
            return $name;
        }
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