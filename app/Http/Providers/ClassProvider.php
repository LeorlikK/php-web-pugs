<?php

class ClassProvider
{
    public static function register():void
    {
      spl_autoload_register(function ($class) {
        $file = str_replace('\\', '/', $class) . '.php';
        $file = str_replace('App', 'app', $file);
        $file = str_replace('Database', 'database', $file);

        $path = $_SERVER['DOCUMENT_ROOT']  . '/' . $file;
        if (file_exists($path)) {
          require_once $path;
        }else{
          require_once '../../../views/errors/error.php';
          exit();
        }
      });
    }
}