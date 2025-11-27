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
use Core\Task\TaskInterface;

final class DeleteUnverifiedUsers implements TaskInterface
{
    public function handle(): void
    {
        if (config('security.auth.email_verification')) {
            User::query()
                ->select(['created_at',  'email_verified_at'])
                ->whereNull('email_verified_at')
                ->andGreaterOrEqual('created_at', carbon()->addWeek()->toDateTimeString())
                ->delete();
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
