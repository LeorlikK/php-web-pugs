<?php

namespace App\Http\Controllers\PersonalArea;

use App\Http\Controllers\Auth\Authorization;
use App\Http\Requests\Media\PhotoRequest;
use App\Http\Requests\PersonalArea\LoginRequest;
use App\Http\Services\MediaService;
use App\Http\Services\StrService;
use Database\DB;
use Views\View;

class PersonalAreaController
{
    public function main():View
    {
        if (!Authorization::authCheck()) header('Location: /');

        $user = DB::select("SELECT * FROM users WHERE email = '{$_SESSION['authorize']}'")->fetch();
        return new View('personal_area.main', ['user' => $user]);
    }

    public function loginUpdate():View
    {
        if (!Authorization::authCheck()) header('Location: /');

        $request = [
            'login' => StrService::stringFilter($_POST['login']),
        ];
        $errors = LoginRequest::validated($request);

        if (!$errors){
            DB::update("UPDATE users SET login = ? WHERE email = ?", [$request['login'], $_SESSION['authorize']]);
            header('Location: /office');
            exit();
        }else{
            $user = DB::select("SELECT * FROM users WHERE email = '{$_SESSION['authorize']}'")->fetch();
            return new View('personal_area.main', ['errors' => $errors, 'user' => $user]);
        }
    }

    public function avatarUpdate():View
    {
        $avatar = $_FILES['avatar'];
        $errors = PhotoRequest::validated($avatar);

        if ($errors){
            $user = DB::select("SELECT * FROM users WHERE email = '{$_SESSION['authorize']}'")->fetch();
            return new View('personal_area.main', ['errors' => $errors, 'user' => $user]);
        }

        $user = DB::select("SELECT * FROM users WHERE email = '{$_SESSION['authorize']}'")->fetch();
        if (file_exists($user['avatar'])){
            if ($user['avatar'] !== 'resources/images/avatar/avatar_default.png'){
                unlink($user['avatar']);
            }
        }
        $url = MediaService::generateUrl($avatar, 'resources/images/avatar/', 'users', 'avatar');
        DB::update("UPDATE users SET avatar = ? WHERE email = ?", [$url, $_SESSION['authorize']]);
        move_uploaded_file($avatar['tmp_name'], $url);
        header('Location: /office');
        exit();
    }

    public function emailSend()
    {
        $to = "leorl1k93@gmail.com";
        $from = $_SESSION['authorize'];
        $message = htmlspecialchars($_POST['emailSend']);
        $message = urldecode($message);
        $message = trim($message);
        $headers = "From: $from" . "\r\n" .
            "Reply-To: $to" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();
        if (mail($to, 'Pugs', $message, $headers)){
            header('Location: /office');
        }else{
            header('Location: /office');
        }
    }
}