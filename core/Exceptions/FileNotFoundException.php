<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Exceptions;

use Exception;

/**
 * This exception occurs when file not found
 */
class FileNotFoundException extends Exception
{
    public function __construct(string $file) {
        parent::__construct("File $file not found");
    }
}
