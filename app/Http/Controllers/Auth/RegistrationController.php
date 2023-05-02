<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ErrorCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Http\Services\MailService;
use App\Http\Services\MediaService;
use App\Http\Services\StrService;
use Database\DB;
use DateTime;
use App\Http\Controllers\Render\View;

class RegistrationController extends Controller
{
    public function registrationShow():View
    {
        return new View('auth.registration', []);
    }

    public function registrationCreate():View
    {
        $request = [
            'login' => StrService::stringFilter($_POST['login']),
            'email' => StrService::stringFilter($_POST['email']),
            'password-first' => StrService::stringFilter($_POST['password-first']),
            'password-second' => StrService::stringFilter($_POST['password-second']),
            'avatar' => $_FILES['avatar'],
        ];
        $errors = RegistrationRequest::validated($request);

        if (!$errors){
            $dateTime = new DateTime();
            $dateNow = $dateTime->format('Y-m-d H:i:s');
            $password = password_hash($request['password-first'], PASSWORD_DEFAULT);

            $roleArray = Authorization::ROLE;
            $roleArray = array_keys($roleArray);
            $defaultRole = end($roleArray);
            if ($request['avatar']['size'] > 0){
                $url = MediaService::generateUniqueUrl($request['avatar'], 'resources/images/avatar/', 'users', 'avatar');
                move_uploaded_file($request['avatar']['tmp_name'], $url);
            }else{
                $url = 'resources/images/avatar/avatar_default.png';
            }

            $mail = new MailService();
            $verifyKey = md5($dateNow);
            $mail->verify($request['email'], $verifyKey);

            $result = DB::insert("INSERT INTO users (email, password, created_at, updated_at, role, login, avatar, verify) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                [$request['email'], $password, $dateNow, $dateNow, $defaultRole, $request['login'], $url, $verifyKey]);

            if ($result){
                $_SESSION['success'] = 'Please verify that this is your email address';
                header('Location: /');
                exit();
            }else{
              	new ErrorCode('unknown', 'Failed to register user', 'unknown');
                exit();
            }
        }else{
            return new View('auth.registration', $errors);
        }
    }

    public function verify():void
    {
        $request = [
            'email' => StrService::stringFilter($_GET['email']),
            'verify' => StrService::stringFilter($_GET['verify']),
        ];

        $user = DB::select("SELECT verify FROM users WHERE email = ?",
            [$request['email']])->fetch();

        if ($user['verify'] === $request['verify']){
            DB::update("UPDATE users SET verify = ? WHERE email = ?", ['verify', $request['email']]);
            header('Location: /login');
            exit();
        }else{
          	new ErrorCode('unknown', 'Failed to pass verification', 'unknown');
            exit();
        }
    }
}