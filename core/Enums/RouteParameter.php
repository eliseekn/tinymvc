<?php

/**
 * @copyright 2019-2025 n'guessan kouadio elisée <eliseekn@gmail.com>
 * @license mit (https://opensource.org/licenses/mit)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Enums;

enum RouteParameter: int
{
    public const ALPHA = 'alpha';

    public const ALPHA_NUMERIC = 'alphaNum';

    public const NUMBER = 'num';

    public const ANY = 'any';
}
