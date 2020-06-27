<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Support;

use Framework\Http\Redirect;
use GUMP;
use Framework\Http\Request;

/**
 * Validation
 * 
 * Form fields validation
 */
class Validation
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
    protected static $error_messages = [];

    /**
     * validate fields 
     *
     * @return void
     */
    public static function validate(): void
    {
        $error_messages = GUMP::is_valid(Request::getField(), static::$rules, static::$error_messages);

        if (is_array($error_messages)) {
            Redirect::back()->withError($error_messages);
        }
    }
}
