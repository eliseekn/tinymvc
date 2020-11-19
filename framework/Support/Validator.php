<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Support;

use GUMP;

/**
 * Request fields validator
 */
class Validator
{
    /**
     * rules
     * 
     * @var array
     */
    protected static $rules = [];

    /**
     * custom errors messages
     * 
     * @var array
     */
    protected static $messages = [];

    /**
     * validation errors messages
     * 
     * @var bool|array
     */
    protected static $errors;

    /**
     * validate fields 
     *
     * @param  array $fields
     * @return \Framework\Support\Validator
     */
    public static function validate(array $fields, array $rules = [], array $messages = []): self
    {
        $validators = empty($rules) ? static::$rules : $rules;
        $fields_error_messages = empty($messages) ? static::$messages : $messages;
        static::$errors = GUMP::is_valid($fields, $validators, $fields_error_messages);
        return new self();
    }
    
    /**
     * check if validation fails
     *
     * @return bool
     */
    public function fails(): bool
    {
        return is_array(static::$errors);
    }
    
    /**
     * get validation errors
     *
     * @return array
     */
    public function errors(): array
    {
        return static::$errors;
    }
}
