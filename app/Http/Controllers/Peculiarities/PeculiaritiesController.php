<?php

namespace App\Http\Controllers\Peculiarities;

use Database\DB;
use Views\View;

class PeculiaritiesController
{
    public function peculiarities():View
    {
        $query = "SELECT * FROM osobennosti WHERE title = ?;";
        $result = DB::select($query, ['one'])->fetch();

        return new View('peculiarities.peculiarities', ['result' => $result]);
    }

    public function care():?View
    {
        $query = "SELECT * FROM osobennosti WHERE title = ?;";
        $result = DB::select($query, ['Уход и содержание'])->fetch();

        return new View('peculiarities.peculiarities', ['result' => $result]);
    }

    public function nutrition():?View
    {
        $query = "SELECT * FROM osobennosti WHERE title = ?;";
        $result = DB::select($query, ['Питание'])->fetch();

        return new View('peculiarities.peculiarities', ['result' => $result]);
    }

    public function health():?View
    {
        $query = "SELECT * FROM osobennosti WHERE title = ?;";
        $result = DB::select($query, ['Здоровье'])->fetch();

        return new View('peculiarities.peculiarities', ['result' => $result]);
    }

    public function paddock():?View
    {
        $query = "SELECT * FROM osobennosti WHERE title = ?;";
        $result = DB::select($query, ['Выгул'])->fetch();

        return new View('peculiarities.peculiarities', ['result' => $result]);
    }
}