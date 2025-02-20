<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Events\UserRegistered;

use Core\Database\Model;
use Core\Events\Event;

class UserRegisteredEvent
{
    public function __construct(public Model $user) {}

    public function dispatch(): void
    {
        Event::dispatch(self::class, $this);
    }
}
