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
use Core\Enums\ResponseStatus;

final class DeleteUseCase
{
    public function handle(Model $user): void
    {
        if (! $user->delete()) {
            response()->json([
                'status' => ResponseStatus::ERROR,
                'message' => 'Failed to delete user',
            ])->send(HttpCode::INTERNAL_SERVER_ERROR);
        }

        response()->json([
            'status' => ResponseStatus::SUCCESS,
            'message' => 'User delete',
        ])->send(HttpCode::OK);
    }
}
