<?php

namespace App\Http\Requests\Media;

use App\Http\Requests\Request;

class AudioRequest extends Request
{
    public static function validated(array $request, array $error = []):?self
    {
        $self = new self;

        $self->request = $request;
        $self->error = $error;

        $self->isLenNotNull('Выберите аудио файл');
        $self->isSize('size');
        $self->isImage('type');

        if (count($self->error) > 0) return $self;
        return null;
    }

    protected function isSize($field)
    {
        if (empty($this->error[$field])) {
            if (($this->request[$field] ?? null) > 104857600) $this->error[$field] = 'Файл не должен превышать 100 МБ';
        }
    }

    protected function isImage($field)
    {
        if (empty($this->error[$field])) {
            if (explode('/', $this->request[$field])[0] !== 'audio') $this->error[$field] = 'Файл должен быть аудио';
        }
    }
}