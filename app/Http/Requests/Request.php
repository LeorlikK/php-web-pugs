<?php

namespace App\Http\Requests;

class Request
{
    public array $error = [];
    public array $request = [];

//    public function createFields():void
//    {
//        foreach ($this->request as $key => $value){
//            $this->$key = $value;
//        }
//    }

    public function isLenNotNull($placeholder):void
    {
        foreach (array_keys($this->request) as $field){
            if (is_string($this->request[$field])){
                if (mb_strlen($this->request[$field]) < 1) $this->error[$field] = $placeholder;
            }
//            elseif (is_array($this->request[$field])){
//                if ($this->request[$field]['name'] === '') $this->error[$field] = $placeholder;
//            }
        }
    }
}