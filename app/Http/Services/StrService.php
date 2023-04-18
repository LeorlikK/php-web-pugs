<?php

namespace App\Http\Services;

use DateInterval;
use DateTime;

class StrService
{
    public static function format($time)
    {
        $dateTime = new DateTime($time);
        $dateTimeNow = new DateTime();

        $difference = $dateTimeNow->diff($dateTime);
        return self::timeSwitch($difference);
    }

    private static function timeSwitch(DateInterval $dateTimeObj):string
    {
        switch ($dateTimeObj){
            case $dateTimeObj->y > 0:
                return $dateTimeObj->format('%Yy %mm ago');
            case $dateTimeObj->m > 0:
                return $dateTimeObj->format('%mm ago');
            case $dateTimeObj->d > 0:
                return $dateTimeObj->format('%dd %hh ago');
            case $dateTimeObj->h > 0:
                return $dateTimeObj->format('%hh ago');
            case $dateTimeObj->i > 0:
                return $dateTimeObj->format('%im ago');
            case $dateTimeObj->s > 0:
                return $dateTimeObj->format('%ss ago');
        }

        return 'date-error';
    }

    public static function stringCut($string, $length):string
    {
        if (mb_strlen($string) > $length){
            return mb_substr($string, 0, $length) . '...';
        }
        return $string;
    }

    public static function stringFilter(array|string|null $content):array|string|null
    {
        if (is_array($content)){
            foreach ($content as &$array){
                $array = trim($array);
                $array = urldecode($array);
                $array = htmlspecialchars($array);
            }
            return $content;
        }

        if (is_string($content)){
            $content = trim($content);
            $content = urldecode($content);
            return htmlspecialchars($content);
        }
        return null;
    }
}