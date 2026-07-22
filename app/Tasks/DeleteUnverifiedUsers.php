<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Tasks;

use App\Database\Entities\User as UserEntity;
use App\Database\Models\UserModel;
use Core\Database\Model;
use Core\Task\TaskInterface;

final class DeleteUnverifiedUsers implements TaskInterface
{
    public function handle(): void
    {
        if (config('security.auth.email_verification')) {
            UserModel::query()
                ->select('*')
                ->whereNull('email_verified_at')
                ->chunk(100, function (Model $model) {
                    $user = $model->toEntity(UserEntity::class);

                    if ($user->getCreatedAt()?->addWeek()->lte(carbon()->toDateTimeString())) {
                        $user->toModel()->delete();
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
