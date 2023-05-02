<?php

namespace App\Http\Controllers\Render;

class Json
{
    private array $array;

    public function __construct(array|string $array)
    {
        $this->array = $array;
    }

    private function encode():string
    {
        return json_encode($this->array);
    }

    public function render():void
    {
        $json = $this->encode();
        echo $json;
        exit();
    }
}