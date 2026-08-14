<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Observers;

use App\Database\Models\UserModel;
use App\Notifications\Mails\PasswordUpdatedMail;
use Core\Observer\ObserverInterface;

final class UserObserver implements ObserverInterface
{
    public function __construct(public UserModel $model) {}

    public function onCreated(): void {}

    public function onUpdated(): void
    {
        if ($this->model->wasUpdated('password')) {
            $this->model->notify(new PasswordUpdatedMail);
        }
    }

    public function onDeleted(): void {}

    public function onSoftDeleted(): void {}

    public function onForceDeleted(): void {}
}
