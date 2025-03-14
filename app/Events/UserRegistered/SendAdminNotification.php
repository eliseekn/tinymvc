<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Events\UserRegistered;

use App\Database\Models\User;
use App\Enums\UserRole;
use App\Mails\NewUserRegisteredMail;

class SendAdminNotification
{
    public function __invoke(UserRegisteredEvent $event): void
    {
        $admins = User::findAllAByRole(UserRole::ADMIN->value);

        foreach ($admins as $admin) {
            $admin->notify(new NewUserRegisteredMail($event->user, url('/login')));
        }
    }
}
