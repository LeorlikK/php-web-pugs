<?php

namespace App\Http\Controllers\Peculiarities;

use App\Http\Controllers\Controller;
use Database\DB;
use App\Http\Controllers\Render\View;

class PeculiaritiesController extends Controller
{
    public function peculiarities():View
    {
        $result = DB::select("SELECT * FROM osobennosti WHERE id = ?", [1])->fetch();
        return new View('peculiarities.peculiarities', ['result' => $result]);
    }

    public function care():View
    {
        $result = DB::select("SELECT * FROM osobennosti WHERE id = ?", [2])->fetch();
        return new View('peculiarities.peculiarities', ['result' => $result]);
    }

    public function nutrition():View
    {
        $result = DB::select("SELECT * FROM osobennosti WHERE id = ?", [3])->fetch();
        return new View('peculiarities.peculiarities', ['result' => $result]);
    }

    public function health():View
    {
        $result = DB::select("SELECT * FROM osobennosti WHERE id = ?", [4])->fetch();
        return new View('peculiarities.peculiarities', ['result' => $result]);
    }

    public function paddock():View
    {
        $result = DB::select("SELECT * FROM osobennosti WHERE id = ?", [5])->fetch();
        return new View('peculiarities.peculiarities', ['result' => $result]);
    }
}