<?php

declare(strict_types=1);

namespace Core\Enums;

enum RouteParameter: int
{
    public const ALPHA = 'alpha';
    public const ALPHA_NUMERIC = 'alphaNum';
    public const NUMBER = 'num';
    public const ANY = 'any';
}
