<?php

namespace views;

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

    public function viewPrint():void
    {
        $way = $this->createWay();
        $data = $this->arguments;

        if (file_exists('views/' . $way . '.php')){
            require_once 'views/' . $way . '.php';
            exit();
        }else{
            new ErrorCode('Not View');
        }
    }
}