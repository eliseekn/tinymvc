<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Validators;

use Core\Http\Validator\GUMPValidator as Validator;

class AuthRequest extends Validator
{
    /**
     * Validation rules
     */
    protected static $rules = [
        'email' => 'required|min_len,5',
        'password' => 'required|min_len,5'
    ];

    /**
     * Custom errors messages
     */
    protected static $messages = [
        //
    ];

    /**
     * Make validator
     */
    public static function make(array $inputs)
    {
        return self::validate($inputs, static::$rules, static::$messages);
    }
}
