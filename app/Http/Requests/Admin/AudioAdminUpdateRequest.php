<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class AudioAdminUpdateRequest extends Request
{
    public static function validated(array $request, array $error = []):?self
    {
        $self = new self;

        $self->request = $request;
        $self->error = $error;

        $self->isLenNotNull('Заполните поле');

        if (count($self->error) > 0) return $self;
        return null;
    }
}