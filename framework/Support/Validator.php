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
     * @return bool
     */
    public static function validate(array $fields, ?array $rules = null, ?array $messages = null): bool
    {
        $validators = $rules ?? static::$rules;
        $fields_error_messages = $messages ?? static::$messages;
        return GUMP::is_valid($fields, $validators, $fields_error_messages);
    }
}
