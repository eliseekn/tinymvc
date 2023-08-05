<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Exceptions;

use Exception;

/**
 * This exception occurs when view not found
 */
class ViewNotFoundException extends Exception
{
    public function __construct(string $view) {
        parent::__construct("View $view not found");
    }
}
