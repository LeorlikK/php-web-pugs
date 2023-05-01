<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use Database\DB;

class RegistrationRequest extends Request
{
    public static function validated(array $request, array $error = []):?self
    {
        $self = new self;

        $self->request = $request;
        $self->error = $error;

        $self->isLenNotNull('Заполните поле');
        $self->isLogin('login');
        $self->isEmail('email');
        $self->isPasswordFirst('password-first');
        $self->equalsPassword('password-first', 'password-second');
        if ($self->request['avatar']['size'] > 0){
            $self->isSize('size');
            $self->isImage('type');
        }

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

    protected function isEmail($field)
    {
        if (empty($this->error[$field])){
            if (!strpos($this->request[$field], '@')) $this->error[$field] = 'Поле должно быть email';
            elseif (empty($this->error[$field])){
                $query = "SELECT * FROM users WHERE email = ?";
                $email = DB::select($query, [$this->request[$field]])->fetch();
                if ($email) {
                    $this->error[$field] = 'Этот email уже используется';
                }
            }
        }
    }

    protected function isPasswordFirst($field)
    {
        if (empty($this->error[$field])) {
            if (mb_strlen($this->request[$field]) <= 5) $this->error[$field] = 'Пароль должен быть больше 5 символов';
            elseif (empty($this->error[$field])) {
                if (!preg_match('/[а-яА-ЯA-Za-z]/', $this->request[$field])) $this->error[$field] = 'Пароль должен содержать буквы';
            }
            elseif (empty($this->error[$field])) {
                if (!preg_match('/[1-9]/', $this->request[$field])) $this->error[$field] = 'Пароль должен содержать цифры';
            }
        }
    }

    protected function equalsPassword($passwordFirst, $passwordSecond)
    {
        if (empty($this->error[$passwordSecond])){
            if ($this->request[$passwordFirst] !== $this->request[$passwordSecond]) $this->error[$passwordSecond] = 'Пароли должны совпадать';
        }
    }

    protected function isSize($field)
    {
        if (empty($this->error[$field])) {
            if (($this->request['avatar'][$field] ?? null) > 10485760) $this->error[$field] = 'Файл не должен превышать 10 МБ';
        }
    }

    protected function isImage($field)
    {
        if (empty($this->error[$field])) {
            if (explode('/', $this->request['avatar'][$field])[0] !== 'image') $this->error[$field] = 'Файл должен быть изоброажением';
        }
    }
}