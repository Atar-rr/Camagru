<?php

namespace App\library;

class Mail
{
    public static function sendMail(string $email, string $subject, string $message)
    {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "Content-Transfer-Encoding: utf-8\r\n";
        $headers .= "Reply-To: no-reply@gmail.com\r\n";

        mail($email, $subject, $message, $headers);

    }
}