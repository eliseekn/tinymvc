<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Database\Entities;

use App\Database\Models\RoleModel;
use Core\Database\Entity;
use Core\Database\Model;

class Role extends Entity
{
    protected ?string $name = null;

    public static function table(): string
    {
        return 'roles';
    }

    /**
     * @return class-string<Model>
     */
    protected static function model(): string
    {
        return RoleModel::class;
    }

    public function toModel(array $data = []): RoleModel
    {
        // @phpstan-ignore-next-line return.type (model() above guarantees a RoleModel)
        return parent::toModel($data);
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
}
