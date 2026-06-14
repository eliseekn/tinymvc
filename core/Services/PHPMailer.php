<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Services;

use Core\Notification\Mail\MailerInterface;
use Core\Support\Logger;
use Exception;
use PHPMailer\PHPMailer\PHPMailer as _PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * Send emails using PHPMailer.
 */
class PHPMailer implements MailerInterface
{
    private _PHPMailer $phpMailer;

    public function __construct()
    {
        $this->phpMailer = new _PHPMailer(true);
        $this->phpMailer->Debugoutput = 'error_log';
        $this->phpMailer->CharSet = _PHPMailer::CHARSET_UTF8;

        $this->phpMailer->SMTPDebug = SMTP::DEBUG_OFF;
        $this->phpMailer->isSMTP();
        $this->phpMailer->Host = config('notifications.mail.smtp.host');
        $this->phpMailer->Port = config('notifications.mail.smtp.port');
        $this->phpMailer->SMTPAuth = config('notifications.mail.smtp.auth');

        if ($this->phpMailer->SMTPAuth) {
            $this->phpMailer->Username = config('notifications.mail.smtp.username');
            $this->phpMailer->Password = config('notifications.mail.smtp.password');
        }

        if (config('notifications.mail.smtp.secure')) {
            $this->phpMailer->SMTPSecure = config('notifications.mail.smtp.tls') ? _PHPMailer::ENCRYPTION_STARTTLS : _PHPMailer::ENCRYPTION_SMTPS;
        } else {
            $this->phpMailer->SMTPAutoTLS = false;
            $this->phpMailer->SMTPSecure = '';
        }
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function to(string|array $address, string|array $name = []): self
    {
        $address = parse_array($address);
        $name = parse_array($name);

        foreach ($address as $key => $addr) {
            $this->phpMailer->addAddress($addr, $name[$key] ?? '');
        }

        return $this;
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function from(string $address, string $name = ''): self
    {
        $this->phpMailer->setFrom($address, $name);

        return $this;
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function replyTo(string $address, string $name = ''): self
    {
        $this->phpMailer->addReplyTo($address, $name);

        return $this;
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function cc(string|array $address, string|array $name = []): self
    {
        $address = parse_array($address);
        $name = parse_array($name);

        foreach ($address as $key => $addr) {
            $this->phpMailer->addCC($addr, $name[$key]);
        }

        return $this;
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function bcc(string|array $address, string|array $name = []): self
    {
        $address = parse_array($address);
        $name = parse_array($name);

        foreach ($address as $key => $addr) {
            $this->phpMailer->addBCC($addr, $name[$key]);
        }

        return $this;
    }

    public function subject(string $subject): self
    {
        $this->phpMailer->Subject = $subject;

        return $this;
    }

    public function text(string $body): self
    {
        $this->phpMailer->Body = $body;

        return $this;
    }

    public function html(string $body, array $data = []): self
    {
        $this->phpMailer->Body = view($body, $data);
        $this->phpMailer->isHTML();

        return $this;
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
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
            Logger::exception($e);

            return false;
        }
    }
}
