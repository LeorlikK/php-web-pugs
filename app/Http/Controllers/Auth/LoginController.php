<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Services\StrService;
use Database\DB;
use Views\View;

class LoginController extends Controller
{
    public function loginShow():View
    {
        return new View('auth.login', []);
    }

    public static function loginCreate():View
    {
        $request = [
            'email' => StrService::stringFilter($_POST['email']),
            'password' => StrService::stringFilter($_POST['password']),
        ];

        $query = "SELECT email, password, role, id, banned, verify FROM users WHERE email = ?";
        $user = DB::select($query, [$request['email']])->fetch();

        if ($user && $user['banned']){
            $error['email'] = 'Пользователь заблокирован';
            return new View('auth.login', ['request' => $request, 'error' => $error]);
        }

        if ($user){
            $verifyPassword = password_verify($request['password'], $user['password']);
            if ($verifyPassword){
                if ($user['verify'] !== 'verify'){
                    $error['email'] = 'Подтвердите свою почту';
                    return new View('auth.login', ['request' => $request, 'error' => $error]);
                }
                session_unset();
                session_regenerate_id();
                $_SESSION['authorize'] = $request['email'];
                $_SESSION['role'] = Authorization::ROLE[$user['role']];
                header('Location: /');
                exit();
            }else{
                $error['password'] = 'Неверный пароль';
                return new View('auth.login', ['request' => $request, 'error' => $error]);
            }
        }else{
            $error['email'] = 'Неверный email';
            return new View('auth.login', ['request' => $request, 'error' => $error]);
        }
    }

    public function logout():bool
    {
        return Authorization::authDelete();
    }
}