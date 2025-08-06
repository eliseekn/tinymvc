<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\UseCases\Api\v1\User;

use Core\Database\Model;
use Core\Enums\HttpCode;

final class GetItemUseCase
{
    public function handle(Model $user): void
    {
        response()
            ->json($user->get())
            ->send(HttpCode::OK);
    }
}
