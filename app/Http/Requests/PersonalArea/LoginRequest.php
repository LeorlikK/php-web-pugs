<?php

namespace App\Http\Requests\PersonalArea;

use App\Http\Requests\Request;
use Database\DB;

class LoginRequest extends Request
{
    public static function validated(array $request, array $error = []):?self
    {
        $self = new self;

        $self->request = $request;
        $self->error = $error;

        $self->isLenNotNull('Заполните поле');
        $self->isLogin('login');

        if (count($self->error) > 0) return $self;
        return null;
    }

    protected function isLogin($field)
    {
        if (empty($this->error[$field])){
            if (mb_strlen($this->request[$field]) <= 3) $this->error[$field] = 'Поле должно быть больше 3 символов';
            elseif (empty($this->error[$field])){
                if (mb_strlen($this->request[$field]) > 30){
                    $this->error[$field] = 'Поле не должно превышать 30 символов';
                }
            }
        }
    }
}