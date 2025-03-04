<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Events\UserCreated;

use Core\Database\Model;
use Core\Event\EventInterface;
use Core\Event\Dispatchable;

class UserCreatedEvent implements EventInterface
{
    use Dispatchable;

    public function __construct(public Model $user, public string $password) {}
}
