<?php

namespace App\Http\Services;

class Env
{
    private static array $config = [];

    public static function parse_env():array
    {
        self::$config = parse_ini_file('.env');
        return self::$config;
    }

    public static function getValue(string $name):string
    {
        return self::$config[$name];
    }
}