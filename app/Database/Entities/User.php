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
use Carbon\Carbon;
use Core\Database\Attributes\UseModel;
use Core\Database\Attributes\UseTable;
use Core\Database\Entity;

#[UseTable('users')]
#[UseModel(UserModel::class)]
class User extends Entity
{
    protected ?string $name = null;

    protected ?string $email = null;

    protected ?string $password = null;

    protected ?Carbon $emailVerifiedAt = null;

    protected ?string $avatar = null;

    protected ?int $roleId = null;

    public function toModel(): UserModel
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = bcrypt($password);

        return $this;
    }

    public function getEmailVerifiedAt(): ?Carbon
    {
        return $this->emailVerifiedAt;
    }

    public function setEmailVerifiedAt(?Carbon $emailVerifiedAt): self
    {
        $this->emailVerifiedAt = $emailVerifiedAt;

        return $this;
    }

    public function hasVerifiedEmail(): bool
    {
        return ! is_null($this->emailVerifiedAt);
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getRoleId(): ?int
    {
        return $this->roleId;
    }

    public function setRoleId(?int $roleId): self
    {
        $this->roleId = $roleId;

        return $this;
    }

    public function role(): ?Role
    {
        if (is_null($this->roleId)) {
            return null;
        }

        return $this->toModel()
            ->belongsTo(RoleModel::class)
            ?->toEntity(Role::class);
    }
}
