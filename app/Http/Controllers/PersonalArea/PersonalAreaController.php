<?php

namespace App\Http\Controllers\PersonalArea;

use App\Http\Controllers\Auth\Authorization;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\PhotoRequest;
use App\Http\Requests\Media\UserPhotoRequest;
use App\Http\Requests\PersonalArea\LoginRequest;
use App\Http\Services\MailService;
use App\Http\Services\MediaService;
use App\Http\Services\StrService;
use Database\DB;
use Views\View;

class PersonalAreaController extends Controller
{
    public function __construct()
    {
        if (!Authorization::authCheck()) header('Location: /');
    }

    public function main():View
    {
        $user = DB::select("SELECT * FROM users WHERE email = ?", [Authorization::$auth->email])->fetch();
        return new View('personal_area.main', ['user' => $user]);
    }

    public function loginUpdate():View
    {
        $request = [
            'login' => StrService::stringFilter($_POST['login']),
        ];
        $errors = LoginRequest::validated($request);

        if (!$errors){
            DB::update("UPDATE users SET login = ? WHERE email = ?", [$request['login'], Authorization::$auth->email]);
            header('Location: /office');
            exit();
        }else{
            $user = DB::select("SELECT * FROM users WHERE email = ?", [Authorization::$auth->email])->fetch();
            return new View('personal_area.main', ['errors' => $errors, 'user' => $user]);
        }
    }

    public function avatarUpdate():View
    {
        $avatar = $_FILES['avatar'];
        $errors = UserPhotoRequest::validated($avatar);

        if ($errors){
            $user = DB::select("SELECT * FROM users WHERE email = ?", [Authorization::$auth->email])->fetch();
            return new View('personal_area.main', ['errors' => $errors, 'user' => $user]);
        }

        $user = DB::select("SELECT * FROM users WHERE email = ?", [Authorization::$auth->email])->fetch();
        if (file_exists($user['avatar'])){
            if ($user['avatar'] !== 'resources/images/avatar/avatar_default.png'){
                unlink($user['avatar']);
            }
        }
        $url = MediaService::generateUniqueUrl($avatar, 'resources/images/avatar/', 'users', 'avatar');
        DB::update("UPDATE users SET avatar = ? WHERE email = ?", [$url, Authorization::$auth->email]);
        move_uploaded_file($avatar['tmp_name'], $url);
        header('Location: /office');
        exit();
    }

    public function emailSend():View
    {
        $mail = new MailService();
        $user = DB::select("SELECT email, login, avatar FROM users WHERE email = ?", [Authorization::$auth->email])->fetch();
        $email = $user['email'];
        $login = $user['login'];
        $message = StrService::stringFilter($_POST['emailSend']);
        $result = $mail->admin($email, $login, $message);

        if ($result){
            header('Location: /office');
            exit();
        }else{
            $errors['main'] = 'Не удалось отправить сообщение - попробуйте позже';
            return new View('personal_area.main', ['user' => $user, 'errors' => $errors]);
        }
    }
}