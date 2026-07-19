<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Role;

use App\Database\Models\RoleModel;
use App\Exceptions\InternalServerException;

final class StoreUseCase
{
    public function handle(array $data): void
    {
        if (! RoleModel::factory()->create($data)) {
            throw new InternalServerException('Failed to create role');
        }
    }
}
