<?php

namespace Core\Routing\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Route
{
    public function __construct(
        public string $methods,
        public ?string $uri = null,
        public ?array $middlewares = null,
        public ?string $name = null
    ) {}
}
