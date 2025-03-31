<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Exceptions;

use Exception;

/**
 * This exception occurs when handler not defined for specific route.
 */
class RouteHandlerNotDefinedException extends Exception
{
    public function __construct(string $route)
    {
        parent::__construct("Handler not defined for $route");
    }
}
