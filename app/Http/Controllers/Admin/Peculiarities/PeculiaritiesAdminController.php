<?php

namespace App\Http\Controllers\Admin\Peculiarities;

use Database\DB;
use Views\View;

class PeculiaritiesAdminController
{
    public function main():View
    {
        $result = DB::select("SELECT * FROM osobennosti", [])->fetchAll();

        return new View('admin.peculiarities.peculiarities', ['result' => $result]);
    }
}