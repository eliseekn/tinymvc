<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Events\ModelNotFound;

use Core\Event\Dispatchable;
use Core\Event\EventInterface;

class ModelNotFoundEvent implements EventInterface
{
    use Dispatchable;

    public function __construct(public string $table, public string $column, public string $value)
    {
    }
}
