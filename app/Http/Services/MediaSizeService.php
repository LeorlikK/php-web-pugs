<?php

namespace App\Http\Services;

use Database\DB;

class MediaSizeService
{
    public static function plusImageSize(int $size):void
    {
        $allSize = DB::select("SELECT size FROM media_size WHERE name = 'image'")->fetch();
        $allSize = (intval($allSize['size']) + $size);
        DB::update("UPDATE media_size SET size = ? WHERE name = 'image'", [$allSize]);
    }

    public static function minusImageSize(int $size):void
    {
        $allSize = DB::select("SELECT size FROM media_size WHERE name = 'image'")->fetch();
        $allSize = intval($allSize['size']) - $size;

        DB::update("UPDATE media_size SET size = ? WHERE name = 'image'", [$allSize]);
    }

    public static function plusVideoSize(int $size):void
    {
        $allSize = DB::select("SELECT size FROM media_size WHERE name = 'video'")->fetch();
        $allSize = (intval($allSize['size']) + $size);
        DB::update("UPDATE media_size SET size = ? WHERE name = 'video'", [$allSize]);
    }

    public static function minusVideoSize(int $size):void
    {
        $allSize = DB::select("SELECT size FROM media_size WHERE name = 'video'")->fetch();
        $allSize = intval($allSize['size']) - $size;

        DB::update("UPDATE media_size SET size = ? WHERE name = 'video'", [$allSize]);
    }

    public static function plusAudioSize(int $size):void
    {
        $allSize = DB::select("SELECT size FROM media_size WHERE name = 'audio'")->fetch();
        $allSize = (intval($allSize['size']) + $size);
        DB::update("UPDATE media_size SET size = ? WHERE name = 'audio'", [$allSize]);
    }

    public static function minusAudioSize(int $size):void
    {
        $allSize = DB::select("SELECT size FROM media_size WHERE name = 'audio'")->fetch();
        $allSize = intval($allSize['size']) - $size;

        DB::update("UPDATE media_size SET size = ? WHERE name = 'audio'", [$allSize]);
    }

    public static function translate(int $size):string
    {
        $unit = 'KB';
        if ($size > 1048576 && $size < 1073741824){
            $round = round($size/1048576, 1);
            $unit = 'Mb';
            return $round.$unit;
        }
        if ($size > 1073741824){
            $round = round($size/1048576, 1);
            $unit = 'Gb';
            return $round.$unit;
        }

        return $size.$unit;
    }
}