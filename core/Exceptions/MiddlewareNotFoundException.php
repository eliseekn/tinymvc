<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Exceptions;

use Exception;

/**
 * This exception occurs when middleware class not found
 */
class MiddlewareNotFoundException extends Exception
{
    public function __construct(string $middleware) {
        parent::__construct("Middleware $middleware not found");
    }
}
