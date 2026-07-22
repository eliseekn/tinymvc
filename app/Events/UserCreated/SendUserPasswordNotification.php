<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Events\UserCreated;

use App\Notifications\Mails\AccountCreatedMail;

class SendUserPasswordNotification
{
    public function __invoke(UserCreatedEvent $event): void
    {
        $event->user->toModel()->notify(new AccountCreatedMail($event->password, url('/login')));
    }
}
