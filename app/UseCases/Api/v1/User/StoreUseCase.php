<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Api\v1\User;

use App\Database\Models\User;
use Core\Database\Model;

final class StoreUseCase
{
    public function handle(array $data): Model|false
    {
        $data['password'] = bcrypt($data['password']);

        return User::factory()->create($data);
    }
}
