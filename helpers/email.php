<?php

abstract class Email {

    public function send($data) {
        $to = $data['to'];
        $subject = $data['subject'];
        $message = $data['message'];
        $header = "From: " . $data['from'];

        mail($to, $subject, $message, $header);
    }
}
