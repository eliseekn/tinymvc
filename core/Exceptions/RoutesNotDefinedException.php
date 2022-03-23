<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Exceptions;

use Exception;

/**
 * This exception occurs when routes not defined
 */
class RoutesNotDefinedException extends Exception
{
    public function __construct() {
        parent::__construct('Routes not defined');
    }
}
