<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Tasks;

use App\Database\Models\User;
use Core\Database\Model;
use Core\Task\TaskInterface;

final class DeleteUnverifiedUsers implements TaskInterface
{
    public function handle(): void
    {
        if (config('security.auth.email_verification')) {
            User::query()
                ->select(['id', 'email_verified_at', 'created_at'])
                ->whereNull('email_verified_at')
                ->chunk(100, function (Model $user) {
                    if (carbon($user->get('created_at'))->addWeek()->lte(carbon()->toDateTimeString())) {
                        $user->delete();
                    }
                });
        }
    }

    public function handleCompleted(): void
    {
        //
    }

    public function handleFailed(): void
    {
        //
    }
}
