<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Tasks;

use App\Notifications\Mails\WelcomeMail;
use Core\Notification\Notification;
use Core\Task\TaskInterface;

class SendEmail implements TaskInterface
{
    public function handle(): void
    {
        Notification::send(new WelcomeMail('test'))->to('test@mail.com');
    }

    public function handleCompleted(): void {}

    public function handleFailed(): void {}
}
