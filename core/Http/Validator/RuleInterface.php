<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http\Validator;

interface RuleInterface
{
    public function rule(string $field, array $input, array $params, $value): bool;

    public function message(): string;
}
