<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Database\DB;

class Authorization extends Controller
{
    public static ?self $auth = null;
    public static int $number = 0;
    private bool $authorize = false;
    public ?int $id = null;
    public?string $email = null;
    public ?int $role = null;
    public ?int $test = 0;

    public const ROLE = [
        'admin' => 1,
        'moder' => 2,
        'user' => 5,
    ];

    private static function create():void
    {
        if (!self::$auth){
            self::$auth = new self();
            self::$number++;
        }
    }

    public static function authCheck():bool
    {
        self::create();
        if (self::$auth->authorize){
            return true;
        }else{
            if (isset($_COOKIE['authorize'])){
                $cookie = $_COOKIE['authorize'];
                setcookie('authorize', $cookie, time() + 3600*24*30, '/');
                $explode = explode('_', $cookie);
                $user = DB::select("SELECT session, role, email, id FROM users WHERE id = ?",
                    [$explode[0]])->fetch();
                if ($cookie === $user['session']){
                    self::setCookieForAuth($user);
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    }

    public static function authDelete():bool
    {
        self::create();
        self::$auth->authorize = false;
        self::$auth->role = false;
        setcookie('authorize', '', -3600);

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
        self::create();
        if (self::$auth->role){
            if (self::$auth->role === 1){
                return true;
            }else{
                return false;
            }
        }else{
            if (isset($_COOKIE['authorize'])){
                $cookie = $_COOKIE['authorize'];
                setcookie('authorize', $cookie, time() + 3600*24*30, '/');
                $explode = explode('_', $cookie);
                $user = DB::select("SELECT session, role, email FROM users WHERE id = ?",
                    [$explode[0]])->fetch();
                if ($cookie === $user['session']){
                    self::setCookieForAuth($user);
                    if (self::$auth->role === 1){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    }

    public static function setCookieForAuth(array $user)
    {
        self::$auth->authorize = true;
        self::$auth->id = $user['id'];
        self::$auth->email = $user['email'];
        self::$auth->role = Authorization::ROLE[$user['role']];
    }
}