<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http;

use GUMP;

/**
 * Request fields validator
 */
class Validator
{
    protected static $rules = [];
    protected static $messages = [];
    protected static $errors;
    protected static $inputs = [];

    public static function validate(array $inputs, array $rules = [], array $messages = []): self
    {
        $validators = empty($rules) ? static::$rules : $rules;
        $error_messages = empty($messages) ? static::$messages : $messages;
        static::$inputs = $inputs;
        static::$errors = GUMP::is_valid(static::$inputs, $validators, $error_messages);

        return new self();
    }
    
    public function fails()
    {
        return is_array(static::$errors);
    }
    
    public function errors()
    {
        $errors = [];

        foreach ((array) static::$errors as $error) {
            foreach (static::$inputs as $key => $input) {
                if (strpos(strtolower($error), $key)) {
                    $errors = array_merge($errors, [$key => $error]);
                }
            }
        }

        return $errors;
    }
    
    public function inputs()
    {
        return static::$inputs;
    }
    
    public function redirectOnFail()
    {
        return !$this->fails() ? $this 
            : redirect()->back()->withErrors($this->errors())->withInputs($this->inputs())->go(400);
    }
}
