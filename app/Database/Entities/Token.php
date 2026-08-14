<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Database\Entities;

use App\Database\Models\TokenModel;
use Carbon\Carbon;
use Core\Database\Attributes\UseModel;
use Core\Database\Attributes\UseTable;
use Core\Database\Entity;

#[UseTable('tokens')]
#[UseModel(TokenModel::class)]
class Token extends Entity
{
    protected ?string $identifier = null;

    protected ?string $value = null;

    protected ?Carbon $expiresAt = null;

    protected ?string $description = null;

    public function toModel(): TokenModel
    {
        // @phpstan-ignore-next-line
        return parent::toModel();
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(?string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getExpiresAt(): ?Carbon
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?Carbon $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function isExpired(): bool
    {
        return ! is_null($this->expiresAt) && $this->expiresAt->lt(carbon());
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
