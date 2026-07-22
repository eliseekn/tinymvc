<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Resources;

use App\Database\Entities\Role;
use Core\Database\Model;
use Core\Http\Resource;

class RoleResource extends Resource
{
    public function toArray(Model $model): array
    {
        $role = $model->toEntity(Role::class);

        return [
            'id' => $role->getId(),
            'name' => $role->getName(),
        ];
    }
}
