<?php

/**
 * @copyright 2019-2026 n'guessan kouadio elisée <eliseekn@gmail.com>
 * @license mit (https://opensource.org/licenses/mit)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Enums;

enum RouteName: int
{
    public const INDEX = 'index';

    public const CREATE = 'create';

    public const STORE = 'store';

    public const UPDATE = 'update';

    public const EDIT = 'edit';

    public const SHOW = 'show';

    public const DELETE = 'delete';
}
