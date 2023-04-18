<?php

namespace App\Http\Services;

class MailService
{
    public function admin($email, $login, $message)
    {
        $to = "leorl1k93@gmail.com";
        $from = $_SESSION['authorize'];

        $getHTML = file_get_contents('views/components/mail/admin.php');
        $mail = str_replace('<?=$email?>', $email, $getHTML);
        $mail = str_replace('<?=$login?>', $login, $mail);
        $mail = str_replace('<?=$message?>', $message, $mail);

        $headers = "From: $from" . "\r\n" .
            "Reply-To: $to" . "\r\n" .
            "X-Mailer: PHP/" . phpversion() . "\r\n" .
            "Content-type: text/html; charset=UTF-8\r\n";
        if (mail($to, 'Pugs', $mail, $headers)){
            header('Location: /office');
        }else{
            header('Location: /office');
        }
    }

    public function verify($email, $verify)
    {
        $verify = "http://php-website/registration/verify?email=$email&verify=$verify";
        $to = $email;
        $from = "leorl1k93@gmail.com";

        $getHTML = file_get_contents('views/components/mail/verify.php');
        $mail = str_replace('<?=$verify?>', $verify, $getHTML);
        $mail = str_replace('<?=$email?>', $email, $mail);

        $headers = "From: $from" . "\r\n" .
            "Reply-To: $to" . "\r\n" .
            "X-Mailer: PHP/" . phpversion() . "\r\n" .
            "Content-type: text/html; charset=UTF-8\r\n";
        if (mail($to, 'Pugs', $mail, $headers)){
            header('Location: /office');
        }else{
            header('Location: /office');
        }
    }
}