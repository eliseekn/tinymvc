<?php

namespace App\Mails;

use Framework\Routing\View;
use Framework\System\Email;

class NotificationsMail
{
    /**
     * send email notification
     *
     * @param  string $email
     * @return bool
     */
    public static function send(string $email, string $notification, string $url): bool
    {
        return Email::to($email)
            ->from(config('mailer.sender_email'), config('mailer.sender_name'))
            ->reply(config('mailer.sender_email'), config('mailer.sender_name'))
			->subject(__('notification'))
            ->html(View::getContent('emails.notifications', compact('notification', 'url')))
			->send();
    }
}
