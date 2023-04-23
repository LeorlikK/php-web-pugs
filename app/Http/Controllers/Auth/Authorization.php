<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class Authorization extends Controller
{
    public static string $userName;
    public static ?int $userRole;
    public const ROLE = [
        'admin' => 1,
        'moder' => 2,
        'user' => 5,
    ];

    public static function authCheck():bool
    {
        if (isset($_SESSION['authorize'])){
            return true;
        }else{
            return false;
        }
    }

    public static function authDelete():bool
    {
        if (isset($_SESSION)){
            foreach ($_SESSION as $key => $value){
                unset($_SESSION[$key]);
            }
            return true;
        }else{
            return false;
        }
    }

    public static function checkRegister():bool
    {
        if ($_SESSION['success']?? null){
            return true;
        }else{
            return false;
        }
    }

    public static function deleteRegister():bool
    {
        if (isset($_SESSION['success'])){
            unset($_SESSION['success']);
            return true;
        }else{
            return false;
        }
    }

    public static function checkAdmin():bool
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] === 1){
            return true;
        }else{
            return false;
        }
    }
}