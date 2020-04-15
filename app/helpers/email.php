<?php
/**
* Application => TinyMVC (PHP framework based on MVC architecture)
* File        => email.php (send plain text email using PHP mail function)
* Github      => https://github.com/eliseekn/tinymvc
* Copyright   => 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
* Licence     => MIT (https://opensource.org/licenses/MIT)
*/

//send plain text email through built-in PHP mail function
function send_email($data) {
    $to = $data['to'];
    $subject = $data['subject'];
    $message = $data['message'];
    $header = 'From: '. $data['from'];

    return mail($to, $subject, $message, $header);
}
