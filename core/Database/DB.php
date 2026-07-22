<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Database;

final class DB
{
    public function __construct(
        public ?string $driver = null,
        public ?string $host = null,
        public ?string $port = null,
        public ?string $name = null,
        public ?string $username = null,
        public ?string $password = null,
        public ?bool $memory = null,
        public ?string $charset = null,
        public ?string $collation = null,
        public ?string $encoding = null,
        public ?string $engine = null,
    ) {
    }
}
