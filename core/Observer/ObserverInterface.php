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

interface ObserverInterface
{
    public static function created(Model $model);

    public static function updated(Model $model);

    public static function deleted(Model $model);
}
