<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Render\View;
use Database\DB;

class MainController extends Controller
{
    public function main():View
    {
        return new View('main.main', []);
    }
}