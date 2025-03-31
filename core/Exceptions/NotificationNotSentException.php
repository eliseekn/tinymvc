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
 * This exception occurs when view not found.
 */
class NotificationNotSentException extends Exception
{
    public function __construct()
    {
        parent::__construct('Failed to send notification');
    }
}
