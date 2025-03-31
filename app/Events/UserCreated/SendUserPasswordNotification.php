<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Events\UserCreated;

use App\Mails\AccountCreatedMail;

class SendUserPasswordNotification
{
    public function __invoke(UserCreatedEvent $event): void
    {
        $event->user->notify(new AccountCreatedMail($event->password, url('/login')));
    }
}
