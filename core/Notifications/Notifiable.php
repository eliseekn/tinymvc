<?php

namespace Core\Notifications;

use Exception;

trait Notifiable
{
    public function notify(NotificationInterface $notification): void
    {
        try {
            Notification::send($notification)->to($this->get('email'));
        } catch (Exception $e) {
            report($e);
        }
    }
}
