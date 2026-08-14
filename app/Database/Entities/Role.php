<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Database\Entities;

use App\Database\Models\RoleModel;
use App\Database\Models\UserModel;
use Core\Database\Attributes\UseModel;
use Core\Database\Attributes\UseTable;
use Core\Database\Entity;

#[UseTable('roles')]
#[UseModel(RoleModel::class)]
class Role extends Entity
{
    protected ?string $name = null;

    public function toModel(): RoleModel
    {
        // @phpstan-ignore-next-line
        return parent::toModel();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function users(): array
    {
        $users = $this->toModel()->hasMany(UserModel::class);

        return array_map(fn (UserModel $user) => $user->toEntity(User::class), $users);
    }
}
