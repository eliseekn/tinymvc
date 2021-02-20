<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Support;

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Email sender
 */
class Email
{
    /**
     * @var PHPMailer\PHPMailer\PHPMailer $mail
     */
    protected static $mail;

    /**
     * setup PHPMailer and set address 
     *
     * @param  string $address
     * @param  string $name
     * @return \Framework\Support\Email
     */
    public static function to(string $address, string $name = ''): self
    {
        self::$mail = new PHPMailer(true);
        self::$mail->Debugoutput = 'error_log';
        self::$mail->CharSet = PHPMailer::CHARSET_UTF8;

        if (config('mailer.default') === 'smtp') {
            self::$mail->SMTPDebug = SMTP::DEBUG_SERVER;
            self::$mail->SMTPSecure = config('mailer.smtp.tls') ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
            self::$mail->isSMTP();
            self::$mail->Host = config('mailer.smtp.host');
            self::$mail->Port = config('mailer.smtp.port');
            self::$mail->SMTPAuth = config('mailer.smtp.auth');

            if (self::$mail->SMTPAuth) {
                self::$mail->Username = config('mailer.smtp.username');
                self::$mail->Password = config('mailer.smtp.password');
            }
        }

        self::$mail->addAddress($address, $name);
        return new self();
    }

    /**
     * from
     *
     * @param  string $address
     * @param  string $name
     * @return \Framework\Support\Email
     */
    public function from(string $address, string $name = ''): self
    {
        self::$mail->setFrom($address, $name);
        return $this;
    }

    /**
     * reply to
     *
     * @param  string $address
     * @param  string $name
     * @return \Framework\Support\Email
     */
    public function reply(string $address, string $name = ''): self
    {
        self::$mail->addReplyTo($address, $name);
        return $this;
    }
    
    /**
     * cc
     *
     * @param  mixed $address
     * @param  mixed $name
     * @return self
     */
    public function cc(string $address, string $name = ''): self
    {
        self::$mail->addCC($address, $name);
        return $this;
    }
    
    /**
     * bcc
     *
     * @param  mixed $address
     * @param  mixed $name
     * @return self
     */
    public function bcc(string $address, string $name = ''): self
    {
        self::$mail->addBCC($address, $name);
        return $this;
    }

    /**
     * subject
     *
     * @param  string $subject
     * @return \Framework\Support\Email
     */
    public function subject(string $subject): self
    {
        self::$mail->Subject = $subject;
        return $this;
    }

    /**
     * message
     *
     * @param  string $message
     * @return \Framework\Support\Email
     */
    public function message(string $message): self
    {
        self::$mail->Body = $message;
        return $this;
    }

    /**
     * set message as html
     *
     * @param  string $message
     * @return \Framework\Support\Email
     */
    public function html(string $message): self
    {
        return $this->message($message)->asHTML();
    }

    /**
     * set email format to HTML
     *
     * @return \Framework\Support\Email
     */
    public function asHTML(): self
    {
        self::$mail->IsHTML(true);
        return $this;
    }

    /**
     * add attachment
     *
     * @param  string $attachment
     * @param  string $filename
     * @return \Framework\Support\Email
     */
    public function addAttachment(string $attachment, string $filename = ''): self
    {
        self::$mail->addAttachment($attachment, $filename);
        return $this;
    }
    
    /**
     * send email
     *
     * @return bool
     */
    public function send(): bool
    {
        return self::$mail->send();
    }
}
