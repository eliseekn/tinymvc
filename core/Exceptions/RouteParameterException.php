<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Exceptions;

use Closure;
use Exception;

/**
 * Route exceptions
 */
class RouteException extends Exception
{
    public static function noParameterNotDefined(string $parameter): self
    {
        return new self("No pattern defined for parameter '$parameter'");
    }

    public static function noHandlerDefined(string $route): self
    {
        return new self("No handler defined for route '$route'");
    }

    public static function noNameDefined(string $name): self
    {
        return new self("Route name '$name' is not defined");
    }

    public static function noRoutesDefined(): self
    {
        return new self('No routes defined in ./routes');
    }

    public static function noPathsDefined(): self
    {
        return new self('No routes paths defined in "./config/routes.php"');
    }

    public static function invalidHandler(Closure|array|string $handler): self
    {
        if (is_callable($handler)) {

            return new self('Invalid route handler');
        }

        if (is_array($handler)) {
            $handler = json_encode($handler);
        }

        return new self("Invalid route handler '$handler'");
    }
}
