<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Observers;

use App\Notifications\Mails\PasswordUpdatedMail;
use Core\Database\Model;
use Core\Observer\ObserverInterface;

abstract class UserObserver implements ObserverInterface
{
    public static $table = 'users';

    public static function created(Model $model): void {}

    public static function updated(Model $model): void
    {
        if ($model->wasUpdated('password')) {
            $model->notify(new PasswordUpdatedMail);
        }
    }

    public static function deleted(Model $model): void {}
}
