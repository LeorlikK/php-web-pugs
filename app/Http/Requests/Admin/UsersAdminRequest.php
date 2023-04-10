<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Database\DB;

class UsersAdminRequest extends Request
{
    public static function validated(array $request, array $error = []):?self
    {
        $self = new self;

        $self->request = $request;
        $self->error = $error;

        $self->isLenNotNull('Заполните поле');
        $self->isLogin('login');
        $self->isEmail('email');
        $self->isExistAvatarRoute('avatar');

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
                $query = "SELECT * FROM users WHERE email = ? AND id <> ?";
                $email = DB::select($query, [$this->request[$field], $this->request['id']])->fetch();
                if ($email) {
                    $this->error[$field] = 'Этот email уже используется';
                }
            }
        }
    }

    protected function isExistAvatarRoute($field)
    {
        if (empty($this->error[$field])){
            if (!file_exists($this->request[$field])) $this->error[$field] = 'Файл не найден';
        }
    }
}