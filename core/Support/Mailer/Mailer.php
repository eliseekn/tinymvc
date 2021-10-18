<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support\Mailer;

use Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Send emails using PHPMailer
 */
class Mailer implements MailerInterface
{
    private PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->mailer->Debugoutput = 'error_log';
        $this->mailer->CharSet = PHPMailer::CHARSET_UTF8;

        if (config('mailer.transport') === 'smtp') {
            $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
            $this->mailer->isSMTP();
            $this->mailer->Host = config('mailer.smtp.host');
            $this->mailer->Port = config('mailer.smtp.port');
            $this->mailer->SMTPAuth = config('mailer.smtp.auth');

            if ($this->mailer->SMTPAuth) {
                $this->mailer->Username = config('mailer.smtp.username');
                $this->mailer->Password = config('mailer.smtp.password');
            }

            if (config('mailer.smtp.secure')) {
                $this->mailer->SMTPSecure = config('mailer.smtp.tls') 
                    ? PHPMailer::ENCRYPTION_STARTTLS 
                    : PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $this->mailer->SMTPAutoTLS = false;
                $this->mailer->SMTPSecure = false;
            }
        }
    }

    public function to(string $address, string $name = ''): self
    {
        $this->mailer->addAddress($address, $name);
        return $this;
    }

    public function from(string $address, string $name = ''): self
    {
        $this->mailer->setFrom($address, $name);
        return $this;
    }

    public function reply(string $address, string $name = ''): self
    {
        $this->mailer->addReplyTo($address, $name);
        return $this;
    }
    
    public function cc(string $address, string $name = ''): self
    {
        $this->mailer->addCC($address, $name);
        return $this;
    }
    
    public function bcc(string $address, string $name = ''): self
    {
        $this->mailer->addBCC($address, $name);
        return $this;
    }

    public function subject(string $subject): self
    {
        $this->mailer->Subject = $subject;
        return $this;
    }

    public function body(string $message, bool $html = true): self
    {
        $this->mailer->Body = $message;

        if ($html) $this->mailer->isHTML(true);

        return $this;
    }

    public function attachment(string $attachment, string $filename = ''): self
    {
        $this->mailer->addAttachment($attachment, $filename);
        return $this;
    }
    
    /**
     * @throws Exception
     */
    public function send(): bool
    {
        try {
            return $this->mailer->send();
        } catch (Exception $e) {
            return false;
        }
    }
}
