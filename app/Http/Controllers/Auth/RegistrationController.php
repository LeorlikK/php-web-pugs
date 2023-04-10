<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ErrorView;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Http\Services\MediaService;
use App\Http\Services\StrService;
use Database\DB;
use DateTime;
use Views\View;

class RegistrationController
{
    public function registrationShow():View
    {
        return new View('auth.registration', []);
    }

    public function registrationCreate()
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
            try {
                $dateTime = new DateTime();
                $dateNow = $dateTime->format('Y-m-d H:i:s');
                $password = password_hash($request['password-first'], PASSWORD_DEFAULT);

                $defaultRole = Authorization::ROLE[count(Authorization::ROLE) - 1];
                if ($request['avatar']['size'] > 0){
                    $url = MediaService::generateUrl($request['avatar'], 'resources/images/avatar/', 'users', 'avatar');
                    move_uploaded_file($request['avatar']['tmp_name'], $url);
                }else{
                    $url = 'resources/images/avatar/avatar_default.png';
                }

                $query = "INSERT INTO users (email, password, created_at, updated_at, role , login, avatar) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $result = DB::insert($query, [$request['email'], $password, $dateNow, $dateNow, $defaultRole, $request['login'], $url]);

                if ($result){
//                    $plusYear = $dateTime->modify('+1 year')->getTimestamp();
//                    setcookie('email', $request['email'], $plusYear, '/');
//                    setcookie('password', $password, $plusYear, '/');
                    session_unset();
                    session_regenerate_id();
                    $_SESSION['authorize'] = $request['email'];
                    $_SESSION['role'] = 2;
                    $_SESSION['success'] = 'You have successfully registered';
                    header('Location: /');
                }
            }catch (\Exception $exception){
                new ErrorView($exception);
            }

        }else{
            return new View('auth.registration', $errors);
        }
    }


}