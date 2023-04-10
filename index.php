<?php
session_set_cookie_params(3600*5);
session_start();
//var_dump(ini_get('upload_max_filesize'));
//var_dump(ini_get('post_max_size'));
use Database\DB;

require_once 'app/Http/Providers/ClassProvider.php';
ClassProvider::register();

try {
    $connect = DB::connect();
}catch (PDOException $exception){
    echo $exception->getMessage();
    die();
}
// Session test

require_once 'routes/web.php';

