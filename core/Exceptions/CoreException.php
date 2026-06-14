<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Exceptions;

use Core\Exceptions\Interfaces\Reportable;
use Core\Support\Logger;
use Exception;

/**
 * Core exception.
 */
class CoreException extends Exception implements Reportable
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public function report(): void
    {
        Logger::exception($this);
    }
}
