<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http\Validator;

use Core\Http\Response;

interface ValidatorInterface
{
    public function addCustomRule(string $rule, callable $callback, string $error_message);

    public function validate(array $inputs, Response $response);
    
    public function fails();
        
    public function errors();
    
    public function validated();

    public function rules();

    public function messages();
}
