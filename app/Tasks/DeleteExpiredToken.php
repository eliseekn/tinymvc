<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Tasks;

use App\Database\Models\Token;
use Core\Database\Model;
use Core\Task\TaskInterface;

final class DeleteExpiredToken implements TaskInterface
{
    public function handle(): void
    {
        Token::query()
            ->select(['id', 'expires_at'])
            ->chunk(100, function (Model $token) {
                if (carbon($token->getAttributes('expires_at'))->addHour()->gte(carbon()->toDateTimeString())) {
                    $token->delete();
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
