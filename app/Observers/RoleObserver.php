<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Observers;

use App\Database\Models\RoleModel;
use Core\Cache\Cache;
use Core\Observer\ObserverInterface;

final class RoleObserver implements ObserverInterface
{
    public function __construct(public RoleModel $model) {}

    public function onCreated(): void
    {
        Cache::forget(['roles', 'roles.dashboard']);
    }

    public function onUpdated(): void
    {
        Cache::forget(['roles', 'roles.dashboard']);
    }

    public function onDeleted(): void
    {
        Cache::forget(['roles', 'roles.dashboard']);
    }

    public function onSoftDeleted(): void {}

    public function onForceDeleted(): void {}
}
