<?php

namespace App\Exceptions;

class ErrorView
{
    public function __construct($message='unknown', $cod='unknown')
    {
        require_once 'views/errors/error.php';
    }
}