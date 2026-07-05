<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Notification;

trait Notifiable
{
    public function notify(NotificationInterface $notification, string $attribute = 'email'): void
    {
        Notification::send($notification)->to($this->getAttributes($attribute));
    }
}
