<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support;

use Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Manage emails
 */
class Mailer
{
    protected static $mail;

    /**
     * setup PHPMailer and set address 
     */
    public static function to(string $address, string $name = ''): self
    {
        self::$mail = new PHPMailer(true);
        self::$mail->Debugoutput = 'error_log';
        self::$mail->CharSet = PHPMailer::CHARSET_UTF8;

        if (config('mailer.default') === 'smtp') {
            self::$mail->SMTPDebug = SMTP::DEBUG_SERVER;
            self::$mail->isSMTP();
            self::$mail->Host = config('mailer.smtp.host');
            self::$mail->Port = config('mailer.smtp.port');
            self::$mail->SMTPAuth = config('mailer.smtp.auth');

            if (self::$mail->SMTPAuth) {
                self::$mail->Username = config('mailer.smtp.username');
                self::$mail->Password = config('mailer.smtp.password');
            }

            if (config('mailer.smtp.secure')) {
                self::$mail->SMTPSecure = config('mailer.smtp.tls') ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
            } else {
                self::$mail->SMTPAutoTLS = false;
                self::$mail->SMTPSecure = false;
            }
        }

        self::$mail->addAddress($address, $name);
        return new self();
    }

    public function from(string $address, string $name = ''): self
    {
        self::$mail->setFrom($address, $name);
        return $this;
    }

    public function reply(string $address, string $name = ''): self
    {
        self::$mail->addReplyTo($address, $name);
        return $this;
    }
    
    public function cc(string $address, string $name = ''): self
    {
        self::$mail->addCC($address, $name);
        return $this;
    }
    
    public function bcc(string $address, string $name = ''): self
    {
        self::$mail->addBCC($address, $name);
        return $this;
    }

    public function subject(string $subject): self
    {
        self::$mail->Subject = $subject;
        return $this;
    }

    public function message(string $message): self
    {
        self::$mail->Body = $message;
        return $this;
    }

    public function asHTML(): self
    {
        self::$mail->IsHTML(true);
        return $this;
    }

    /**
     * Set message as html
     */
    public function html(string $message): self
    {
        return $this->message($message)->asHTML();
    }

    public function addAttachment(string $attachment, string $filename = ''): self
    {
        self::$mail->addAttachment($attachment, $filename);
        return $this;
    }
    
    /**
     * @throws Exception
     */
    public function send(): bool
    {
        try {
            return self::$mail->send();
        } catch (Exception $e) {
            return false;
        }
    }
}
