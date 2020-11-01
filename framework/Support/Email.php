<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
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
     * PHPMailer variable instance
     * 
     * @var mixed
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
        self::$mail->SMTPDebug = SMTP::DEBUG_SERVER;
        self::$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        self::$mail->isSMTP();
        self::$mail->SMTPAuth = true;
        self::$mail->Host = config('mailer.host');
        self::$mail->Port = config('mailer.port');
        self::$mail->Username = config('mailer.username');
        self::$mail->Password = config('mailer.password');
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
    public function replyTo(string $address, string $name = ''): self
    {
        self::$mail->addReplyTo($address, $name);
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
