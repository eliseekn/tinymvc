<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http\Validator;

interface ValidatorInterface
{
    public function addCustomRule(string $rule, callable $callback, string $error_message): self;

    public function validate(array $inputs): self;
    
    public function fails(): bool;
        
    public function errors(): array;
    
    public function validated(): array;

    public function rules(): array;

    public function messages(): array;
}
