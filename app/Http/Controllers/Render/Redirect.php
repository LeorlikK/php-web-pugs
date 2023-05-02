<?php

namespace App\Http\Controllers\Render;

class Redirect
{
    public function __construct(readonly private string $path)
    {
    }

    public function redirect():void
    {
        header("Location: $this->path");
        exit();
    }
}