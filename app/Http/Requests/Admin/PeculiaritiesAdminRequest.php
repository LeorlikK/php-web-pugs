<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class PeculiaritiesAdminRequest extends Request
{
    public static function validated(array $request, array $error = []):?self
    {
        $self = new self;

        $self->request = $request;
        $self->error = $error;

        $self->isLenNotNull('Заполните поле');
        $self->isLen('title', 255);

        if (count($self->error) > 0) return $self;
        return null;
    }

    protected function isLen($field, $len)
    {
        if (empty($this->error[$field])){
            if (mb_strlen($this->request[$field]) >= 255) $this->error[$field] = "Поле $field не должно превышать $len символов";
        }
    }
}