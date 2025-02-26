<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\UseCases\Api\v1\User;

use App\Database\Models\User;
use Core\Support\UseCase;

class DeleteUseCase extends UseCase
{
    public function handle(int $id): bool
    {
        $user = User::find($id);
        return $user && $user->delete();
    }
}
