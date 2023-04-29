<?php

namespace App\Http\Services;

use vendor\phpmailer\phpmailer\src\PHPMailer;
use vendor\phpmailer\phpmailer\src\SMTP;
use vendor\phpmailer\phpmailer\src\Exception;

class MailService
{
  	private string $userEmail;
    private string $userPassword;

    public function __construct()
    {
        $this->userEmail = Env::getValue('MAIL_USERNAME');
        $this->userPassword = Env::getValue('MAIL_PASSWORD');
    }

    public function admin($email, $login, $message):bool
    {
      	$to = $this->userEmail;
        $from = $this->userEmail;

        $getHTML = file_get_contents('views/components/mail/admin.php');
        $html = str_replace('<?=$email?>', $email, $getHTML);
        $html = str_replace('<?=$login?>', $login, $html);
        $html = str_replace('<?=$message?>', $message, $html);

        return $this->sendMail($to, $from, $html);
    }

    public function verify($email, $verify):bool
    {
        $verify = Env::getValue('URL') . "/registration/verify?email=$email&verify=$verify";
        $to = $email;
        $from = $this->userEmail;

        $getHTML = file_get_contents('views/components/mail/verify.php');
        $html = str_replace('<?=$verify?>', $verify, $getHTML);
        $html = str_replace('<?=$email?>', $email, $html);

        return $this->sendMail($to, $from, $html);
    }
  
  	public function sendMail(string $to, string $from, string $html):bool
    {
        $mail = new PHPMailer();
        $mail->CharSet = 'utf-8';
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $from;
        $mail->Password = $this->userPassword;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom($from); #откуда
        $mail->addAddress($to); #куда
        $mail->isHTML(true);

        $mail->Subject = 'Pugs';
        $mail->Body = $html;
        $mail->AltBody = '';

        if ($mail->send()){
            return true;
        }else{
            return false;
        }
    }
}