<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Policies;

use Core\Database\Entity;
use Core\Policy\PolicyInterface;

final class ProfilePolicy implements PolicyInterface
{
    public function index(): bool
    {
        return true;
    }

    public function create(): bool
    {
        return true;
    }

    public function store(): bool
    {
        return true;
    }

    public function update(Entity $model): bool
    {
        return auth()?->getId() === $model->getId();
    }

    public function edit(Entity $model): bool
    {
        return true;
    }

    public function show(Entity $model): bool
    {
        return true;
    }

    public function delete(Entity $model): bool
    {
        return auth()?->getId() === $model->getId();
    }
}
