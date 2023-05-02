<?php

namespace App\Http\Controllers\Render;

use App\Exceptions\ErrorCode;

class View
{
    protected string $view;
    protected array|object $arguments;

    public function __construct(string $view, array|object $arguments)
    {
        $this->view = $view;
        $this->arguments = $arguments;
    }

    private function createWay():string
    {
        return str_replace('.', '/', $this->view);
    }

    public function render():void
    {
        $way = $this->createWay();
        if (is_array($this->arguments)){
            foreach ($this->arguments as $key => $value) {
                $this->$key = $value;
            }
        }

        $data = $this;

        if (file_exists('views/' . $way . '.php')){
            require_once 'views/' . $way . '.php';
            exit();
        }else{
            new ErrorCode('View not founded');
        }
    }
}