<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Views\View;
use Database\DB;

class MainController extends Controller
{
    public function main()
    {
//        $query = "SELECT * FROM osobennosti WHERE title = ?;";
//        $result = DB::connect()->prepare($query);
//        $result->execute(['one']);
//        $osobennosti = $result->fetch();
//
//        $query = "SELECT * FROM osobennosti WHERE title = ?;";
//        $result = DB::connect()->prepare($query);
//        $result->execute(['Питание']);
//        $pitanie = $result->fetch();

        return new View('main.main', []);
    }
}