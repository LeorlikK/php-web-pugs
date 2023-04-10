<?php

namespace App\Http\Controllers\Admin;

use Views\View;

class AdminController
{
    public function main():View
    {
        return new View('admin.main', []);
    }
}