<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Enums;

enum DbalType: string
{
    public const STRING = 'string';

    public const TEXT = 'text';

    public const INTEGER = 'integer';

    public const BIGINT = 'bigint';

    public const SMALLINT = 'smallint';

    public const BOOLEAN = 'boolean';

    public const DATETIME = 'datetime';

    public const DATE = 'date';

    public const TIME = 'time';

    public const FLOAT = 'float';

    public const DECIMAL = 'decimal';

    public const BINARY = 'binary';

    public const JSON = 'json';

    public const UUID = 'uuid';
}
