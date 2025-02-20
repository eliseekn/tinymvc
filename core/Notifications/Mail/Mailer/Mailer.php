<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Notifications\Mail\Mailer;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * Send emails using PHPMailer.
 */
class Mailer implements MailerInterface
{
    private PHPMailer $phpMailer;

    public function __construct()
    {
        $this->phpMailer = new PHPMailer(true);
        $this->phpMailer->Debugoutput = 'error_log';
        $this->phpMailer->CharSet = PHPMailer::CHARSET_UTF8;

        $this->phpMailer->SMTPDebug = SMTP::DEBUG_OFF;
        $this->phpMailer->isSMTP();
        $this->phpMailer->Host = config('mailer.smtp.host');
        $this->phpMailer->Port = config('mailer.smtp.port');
        $this->phpMailer->SMTPAuth = config('mailer.smtp.auth');

        if ($this->phpMailer->SMTPAuth) {
            $this->phpMailer->Username = config('mailer.smtp.username');
            $this->phpMailer->Password = config('mailer.smtp.password');
        }

        if (config('mailer.smtp.secure')) {
            $this->phpMailer->SMTPSecure = config('mailer.smtp.tls') ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
        } else {
            $this->phpMailer->SMTPAutoTLS = false;
            $this->phpMailer->SMTPSecure = '';
        }
    }

    public function to(string $address, string $name = ''): self
    {
        $this->phpMailer->addAddress($address, $name);

        return $this;
    }

    public function from(string $address, string $name = ''): self
    {
        $this->phpMailer->setFrom($address, $name);

        return $this;
    }

    public function replyTo(string $address, string $name = ''): self
    {
        $this->phpMailer->addReplyTo($address, $name);

        return $this;
    }

    public function cc(string $address, string $name = ''): self
    {
        $this->phpMailer->addCC($address, $name);

        return $this;
    }

    public function bcc(string $address, string $name = ''): self
    {
        $this->phpMailer->addBCC($address, $name);

        return $this;
    }

    public function subject(string $subject): self
    {
        $this->phpMailer->Subject = $subject;

        return $this;
    }

    public function body(string $message, bool $html = true): self
    {
        $this->phpMailer->Body = $message;

        if ($html) {
            $this->phpMailer->isHTML();
        }

        return $this;
    }

    public function html(string $view, array $data = []): self
    {
        return $this->body(view($view, $data));
    }

    public function attachment(string $attachment, string $filename = ''): self
    {
        $this->phpMailer->addAttachment($attachment, $filename);

        return $this;
    }

    public function send(): bool
    {
        try {
            return $this->phpMailer->send();
        } catch (Exception $e) {
            report($e);

            return false;
        }
    }
}
