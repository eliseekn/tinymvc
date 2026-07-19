<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Role;

use App\Database\Entities\Role;
use App\Exceptions\InternalServerException;

final class UpdateUseCase
{
    public function handle(Role $role, array $data): void
    {
        if (! $role->toModel($data)->save()) {
            throw new InternalServerException('Failed to update role');
        }
    }
}
