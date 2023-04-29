<?php

namespace App\Exceptions;

use Views\View;

class ErrorCod
{
    public function __construct($error='unknown', $message='unknown', $code='unknown')
    {
        if ($message === 'unknown'){
            match ((string)$code) {
                '404' => $message = 'Not Found',
                '403' => $message = 'Forbidden',
                '401' => $message = 'Unauthorized',
                '400' => $message = 'Bad Request',
                '500' => $message = 'Internal Server Error',
                '503' => $message = 'Service Unavailable',
                default => $message = 'Unknown Error ',
            };
        }

        $view = new View('errors.error', ['error' => $error, 'message' => $message, 'code' => $code]);
        $view->viewPrint();
    }

    public static function apacheError():array
    {
        $code = $_SERVER['HTTP_REFERER'];
        match ($code) {
            '404' => $message = 'Not Found',
            '403' => $message = 'Forbidden',
            '401' => $message = 'Unauthorized',
            '400' => $message = 'Bad Request',
            '500' => $message = 'Internal Server Error',
            '503' => $message = 'Service Unavailable',
            default => $message = 'Unknown Error ',
        };

        return ['code' => $code, 'message' => $message];
    }
}