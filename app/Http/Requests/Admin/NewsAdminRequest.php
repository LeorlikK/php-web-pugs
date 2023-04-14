<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class NewsAdminRequest extends Request
{
    public static function validated(array $request, array $error = []):?self
    {
        $self = new self;

        $self->request = $request;
        $self->error = $error;

        $self->isLenNotNull('Заполните поле');
        $self->isLen('title');

        if (count($self->error) > 0) return $self;
        return null;
    }

    protected function isLen($field)
    {
        if (empty($this->error[$field])){
            if (mb_strlen($this->request[$field]) >= 255) $this->error[$field] = 'Поле title не должно быть больше 255 символов';
        }
    }
}