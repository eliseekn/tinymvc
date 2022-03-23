<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support\Mail;

use Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Send emails using PHPMailer
 */
class Mailer implements MailInterface
{
    private PHPMailer $php_mailer;

    public function __construct()
    {
        $this->php_mailer = new PHPMailer(true);
        $this->php_mailer->Debugoutput = 'error_log';
        $this->php_mailer->CharSet = PHPMailer::CHARSET_UTF8;

        if (config('mailer.transport') === 'smtp') {
            $this->php_mailer->SMTPDebug = SMTP::DEBUG_SERVER;
            $this->php_mailer->isSMTP();
            $this->php_mailer->Host = config('mailer.smtp.host');
            $this->php_mailer->Port = config('mailer.smtp.port');
            $this->php_mailer->SMTPAuth = config('mailer.smtp.auth');

            if ($this->php_mailer->SMTPAuth) {
                $this->php_mailer->Username = config('mailer.smtp.username');
                $this->php_mailer->Password = config('mailer.smtp.password');
            }

            if (config('mailer.smtp.secure')) {
                $this->php_mailer->SMTPSecure = config('mailer.smtp.tls') ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $this->php_mailer->SMTPAutoTLS = false;
                $this->php_mailer->SMTPSecure = false;
            }
        }
    }

    public function to(string $address, string $name = ''): self
    {
        $this->php_mailer->addAddress($address, $name);
        return $this;
    }

    public function from(string $address, string $name = ''): self
    {
        $this->php_mailer->setFrom($address, $name);
        return $this;
    }

    public function reply(string $address, string $name = ''): self
    {
        $this->php_mailer->addReplyTo($address, $name);
        return $this;
    }
    
    public function cc(string $address, string $name = ''): self
    {
        $this->php_mailer->addCC($address, $name);
        return $this;
    }
    
    public function bcc(string $address, string $name = ''): self
    {
        $this->php_mailer->addBCC($address, $name);
        return $this;
    }

    public function subject(string $subject): self
    {
        $this->php_mailer->Subject = $subject;
        return $this;
    }

    public function body(string $message, bool $html = true): self
    {
        $this->php_mailer->Body = $message;

        if ($html) $this->php_mailer->isHTML(true);

        return $this;
    }

    public function attachment(string $attachment, string $filename = ''): self
    {
        $this->php_mailer->addAttachment($attachment, $filename);
        return $this;
    }
    
    public function send(): bool
    {
        try {
            return $this->php_mailer->send();
        } catch (Exception $e) {
            return false;
        }
    }
}
