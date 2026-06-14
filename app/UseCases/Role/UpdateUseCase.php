<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Role;

use App\Exceptions\InternalServerException;
use Core\Database\Model;

final class UpdateUseCase
{
    public function handle(Model $role, array $data): void
    {
        if (! $role->update($data)) {
            throw new InternalServerException('Failed to update role');
        }
    }
}
