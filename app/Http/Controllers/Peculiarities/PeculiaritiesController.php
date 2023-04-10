<?php

namespace App\Http\Controllers\Peculiarities;

use Database\DB;
use Views\View;

class PeculiaritiesController
{
    public function peculiarities()
    {
        $query = "SELECT * FROM osobennosti WHERE title = ?;";
        $result = DB::select($query, ['one'])->fetch();

        return new View('peculiarities.peculiarities', ['result' => $result]);
    }

    public function care()
    {
        $query = "SELECT * FROM osobennosti WHERE title = ?;";
        $result = DB::select($query, ['Уход и содержание'])->fetch();

        return new View('peculiarities.peculiarities', ['result' => $result]);
    }

    public function nutrition()
    {
        $query = "SELECT * FROM osobennosti WHERE title = ?;";
        $result = DB::select($query, ['Питание'])->fetch();

        return new View('peculiarities.peculiarities', ['result' => $result]);
    }

    public function health()
    {
        $query = "SELECT * FROM osobennosti WHERE title = ?;";
        $result = DB::select($query, ['Здоровье'])->fetch();

        return new View('peculiarities.peculiarities', ['result' => $result]);
    }

    public function paddock()
    {
        $query = "SELECT * FROM osobennosti WHERE title = ?;";
        $result = DB::select($query, ['Выгул'])->fetch();

        return new View('peculiarities.peculiarities', ['result' => $result]);
    }
}