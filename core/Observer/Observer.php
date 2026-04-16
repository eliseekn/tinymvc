<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Observer;

use Core\Database\Model;
use Spatie\StructureDiscoverer\Discover;

class Observer
{
    protected static array $observers = [];

    public static function load(): void
    {
        self::$observers = Discover::in(config('storage.observers'))->classes()->get();
    }

    public static function created(Model $model): void
    {
        foreach (self::$observers as $observer) {
            if ($observer::$table === $model->getTable() && method_exists($observer, 'created')) {
                $observer::created($model);
            }
        }
    }

    public static function updated(Model $model): void
    {
        foreach (self::$observers as $observer) {
            if ($observer::$table === $model->getTable()) {
                $observer::updated($model);
            }
        }
    }

    public static function deleted(Model $model): void
    {
        foreach (self::$observers as $observer) {
            if ($observer::$table === $model->getTable()) {
                $observer::deleted($model);
            }
        }
    }
}
