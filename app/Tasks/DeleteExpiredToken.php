<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Tasks;

use App\Database\Entities\Token as TokenEntity;
use App\Database\Models\TokenModel;
use Core\Task\TaskInterface;

final class DeleteExpiredToken implements TaskInterface
{
    public function handle(): void
    {
        TokenModel::query()
            ->select('*')
            ->chunk(100, function (TokenModel $model) {
                $token = $model->toEntity(TokenEntity::class);

                if ($token->getExpiresAt()?->addHour()->gte(carbon()->toDateTimeString())) {
                    $model->delete();
                }
            });
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
