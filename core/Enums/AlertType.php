<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Enums;

enum AlertType: string
{
    public const DEFAULT = 'default';

    public const TOAST = 'toast';

    public const INFO = 'primary';

    public const SUCCESS = 'success';

    public const ERROR = 'danger';

    public const WARNING = 'warning';
}
