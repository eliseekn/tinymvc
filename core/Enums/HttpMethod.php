<?php

declare(strict_types=1);

namespace Core\Enums;

enum HttpMethod: string
{
    public const GET = 'GET';
    public const POST = 'POST';
    public const PATCH = 'PATCH';
    public const PUT = 'PUT';
    public const OPTIONS = 'OPTIONS';
    public const DELETE = 'DELETE';
    public const ANY = 'GET|POST|DELETE|PUT|OPTIONS|PATCH';

}
