<?php
session_set_cookie_params(3600*24);
session_start();
use Database\DB;

require_once 'app/Http/Providers/ClassProvider.php';
ClassProvider::register();

try {
    $connect = DB::connect();
}catch (PDOException $exception){
    echo $exception->getMessage();
    die();
}

require_once 'routes/web.php';

