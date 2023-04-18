<?php

namespace App\Exceptions;

class ErrorView
{
    public function __construct($error, $message='unknown', $cod='unknown')
    {
        match ($cod) {
            '404' => header("HTTP/1.0 404 Not Found"),
            '403' => header("HTTP/1.0 404 Forbidden"),
            '401' => header("HTTP/1.0 404 Unauthorized"),
            '400' => header("HTTP/1.0 404 Bad Request"),
            '503' => header("HTTP/1.0 404 Service Unavailable"),
            default => header("HTTP/1.0 500 Internal Server Error"),
        };
    }
}