<?php

class ClassProvider
{
    public static function register():void
    {
        spl_autoload_register(function ($class) {
            require_once $class . '.php';
        });
    }
}