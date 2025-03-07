<?php

declare(strict_types=1);

namespace Core\Enums;

enum DatabaseDriver: string
{
    public const MYSQL = 'mysql';

    public const SQLITE = 'sqlite';

    public const PGSQL = 'pgsql';
}
