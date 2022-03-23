<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Validators\Auth;

use Core\Http\Validator\Validator;

class LoginValidator extends Validator
{
    /**
     * Validation rules
     */
    protected static array $rules = [
        'email' => 'required|max_len,255',
        'password' => 'required|max_len,525'
    ];

    /**
     * Custom errors messages
     */
    protected static array $messages = [
        //
    ];

    /**
     * Make validator
     */
    public static function make(): self
    {
        return new self();
    }
}
