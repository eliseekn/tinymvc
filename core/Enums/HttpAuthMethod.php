<?php

declare(strict_types=1);

namespace Core\Enums;

enum HttpAuthMethod: string
{
    public const BEARER = 'Bearer';
    public const BASIC = 'Basic';
}
