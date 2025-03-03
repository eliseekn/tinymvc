<?php

declare(strict_types=1);

namespace Core\Enums;

enum AppEnv: string
{
    public const LOCAL = 'local';
    public const PROD = 'prod';
    public const TEST = 'test';
}
