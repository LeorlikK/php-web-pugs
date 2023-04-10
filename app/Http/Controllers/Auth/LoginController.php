<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use Database\DB;
use DateTime;
use Views\View;

class LoginController
{
    public function loginShow()
    {
        return new View('auth.login', []);
    }

    public static function loginCreate()
    {
        $request = [
            'email' => (string)trim($_POST['email']),
            'password' => (string)trim($_POST['password']),
        ];

        $query = "SELECT email, password, role, id FROM users WHERE email = ?";
        $user = DB::select($query, [$request['email']])->fetch();

        if ($user){
            $verifyPassword = password_verify($request['password'], $user['password']);
            if ($verifyPassword){
                $dateTime = new DateTime();
//                $plusYear = $dateTime->modify('+1 year')->getTimestamp();
//                setcookie('email', $user['email'], $plusYear, '/');
//                setcookie('password', $user['password'], $plusYear, '/');
//                setcookie('PHPSESSID', '', (time() - 60*60*24*365));
//                session_destroy();
//                session_set_cookie_params(3600);
//                session_start();
                session_unset();
                session_regenerate_id();
                $_SESSION['authorize'] = $request['email'];
                $_SESSION['role'] = Authorization::ROLE[$user['role']];
                header('Location: /');
            }else{
                $error['password'] = 'Неверный пароль';
                return new View('auth.login', ['request' => $request, 'error' => $error]);
            }
        }else{
            $error['email'] = 'Неверный email';
            return new View('auth.login', ['request' => $request, 'error' => $error]);
        }
    }

    public function logout()
    {
//        $time = time() - (60*60*24*365);
//        setcookie('email', '', $time);
//        setcookie('password', '', $time);
        return Authorization::authDelete();
    }
}