<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Exceptions;

use Exception;

/**
 * This exception occurs when json response data is invalid
 */
class InvalidJsonDataException extends Exception
{
    public function __construct() {
        parent::__construct('Invalid json response data');
    }
}
