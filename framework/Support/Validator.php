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
     * validate fields 
     *
     * @param  array $fields
     * @return bool|array
     */
    public static function validate(array $fields, array $rules = [], array $messages = [])
    {
        $validators = empty($rules) ? static::$rules : $rules;
        $fields_error_messages = empty($messages) ? static::$messages : $$messages;
        return GUMP::is_valid($fields, $validators, $fields_error_messages);
    }
}
