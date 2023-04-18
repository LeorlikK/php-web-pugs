<?php

namespace App\Http\Controllers\Auth;

use Database\DB;

class Authorization
{
    public static string $userName;
    public static ?int $userRole;
    public const ROLE = [
        'admin' => 1,
        'moder' => 2,
        'user' => 5,
    ];

//    public static function authCheck():bool
//    {
//        $userCookieName = $_COOKIE['email'] ?? null;
//        $userCookiePassword = $_COOKIE['password'] ?? null;
//
//        if ($userCookieName && $userCookiePassword){
//            $query = "SELECT email, password, role FROM users WHERE email = ?";
//            $user = DB::get($query, [$userCookieName])->fetch();
//            if ($user && $userCookiePassword === $user['password']){
//                self::$userName = $user['email'];
//                self::$userRole = $user['role'] ?? null;
//                return true;
//            }else return false;
//        }
//
//        return false;
//    }

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