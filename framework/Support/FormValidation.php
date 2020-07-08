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

use GUMP;
use Framework\HTTP\Request;
use Framework\HTTP\Redirect;

/**
 * FormValidation
 * 
 * Form fields validation
 */
class FormValidation
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
     * @param  array|null $reditcet
     * @return void
     */
    public static function validate(?array $redirect = null): void
    {
        $error_messages = GUMP::is_valid(Request::getField(), static::$rules, static::$error_messages);

        if (is_array($error_messages)) {
            if (!is_null($redirect)) {
                foreach ($redirect as $key => $value) {
                    switch ($value) {
                        case 'back':
                            Redirect::back()->withError($error_messages);
                            break;
                        default:
                            Redirect::toUrl($value)->withError($error_messages);
                    }
                }
            }
        }
    }
}
