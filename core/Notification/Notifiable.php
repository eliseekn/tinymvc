<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Notification;

use Exception;

trait Notifiable
{
    public function notify(NotificationInterface $notification, string $attribute = 'email'): void
    {
        try {
            Notification::send($notification)->to($this->get($attribute));
        } catch (Exception $e) {
            report($e);
        }
    }
}
