<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class LoginRequest extends Request
{
    public static function validated(array $request, array $error = []):?self
    {
        $self = new self;

        $self->request = $request;
        $self->error = $error;

        if (count($self->error) > 0) return $self;
        return null;
    }


}