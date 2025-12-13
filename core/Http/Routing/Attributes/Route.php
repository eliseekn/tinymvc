<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http\Routing\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Route
{
    public function __construct(
        public string|array $methods,
        public ?string $uri = null,
        public ?array $middlewares = null,
        public ?string $name = null,
        public ?array $parameters = null,
        public ?array $bindings = null,
    ) {
    }
}
