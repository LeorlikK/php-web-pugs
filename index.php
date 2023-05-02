<?php
session_set_cookie_params(3600*24*30);
session_start();

use database\DB;

require_once 'app/Http/Providers/ClassProvider.php';

ClassProvider::register();

try {
    $connect = DB::connect();
}catch (PDOException $exception){
    echo $exception->getMessage();
    die();
}

require_once __DIR__ . '/routes/web.php';

