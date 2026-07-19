<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Events\UserRegistered;

use App\Notifications\Mails\WelcomeMail;

class SendWelcomeNotification
{
    public function __invoke(UserRegisteredEvent $event): void
    {
        $event->user->toModel()->notify(new WelcomeMail($event->user->getName()));
    }
}
