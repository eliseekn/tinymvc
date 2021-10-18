<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http\Validator;

interface ValidatorInterface
{
    public static function add(string $rule, callable $callback, string $error_message);

    public static function validate(array $inputs, array $rules = [], array $messages = []);
    
    public function fails();
        
    public function errors();
    
    public function inputs();
}
