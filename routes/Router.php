<?php

namespace Routes;

use App\Exceptions\ErrorView;
use Throwable;
use Views\View;

class Router
{
    private static array $registerList = [];

    public static function get(string $way, string $class, string $function):void
    {
        self::$registerList['GET'][$way] = ['class' => $class, 'function' => $function];
    }

    public static function post(string $way, string $class, string $function):void
    {
        self::$registerList['POST'][$way] = ['class' => $class, 'function' => $function];
    }

    public static function exec():void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = explode('?', $_SERVER['REQUEST_URI']);
        if (!isset(self::$registerList[$method][$url[0]])) {
            new ErrorView("Not Founded Class: class - {$url[0]}, method - $method");
            exit();
        }
        $class = self::$registerList[$method][$url[0]]['class'];
        $function = self::$registerList[$method][$url[0]]['function'];

//        try {
            $class = new $class();
            $view = $class->$function();
//        }catch (Throwable $exception){
//            new ErrorView($exception, $exception->getMessage(), $exception->getCode());
//            exit();
//        }

        if ($view instanceof View){
            $view->viewPrint();
        }else{
            echo $view;
            exit();
        }
    }
}