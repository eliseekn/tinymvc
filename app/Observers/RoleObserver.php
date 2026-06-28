<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Observers;

use Core\Cache\Cache;
use Core\Database\Model;
use Core\Observer\ObserverInterface;

abstract class RoleObserver implements ObserverInterface
{
    public static $table = 'roles';

    public static function created(Model $model): void
    {
        Cache::forget(['roles', 'roles.dashboard']);
    }

    public static function updated(Model $model): void
    {
        Cache::forget(['roles', 'roles.dashboard']);
    }

    public static function deleted(Model $model): void
    {
        Cache::forget(['roles', 'roles.dashboard']);
    }
}
