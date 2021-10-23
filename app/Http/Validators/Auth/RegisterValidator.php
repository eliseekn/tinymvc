<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Validators\Auth;

use Core\Http\Validator\GUMPValidator;
use Core\Database\Repository;

class RegisterValidator extends GUMPValidator
{
    /**
     * Validation rules
     */
    protected static array $rules = [
        'name' => 'required|max_len,255',
        'email' => 'required|valid_email|max_len,255|unique,users',
        'password' => 'required|max_len,255'
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
    public static function make(array $inputs)
    {
        self::add('unique', function($field, array $input, array $params, $value) {
            $data = (new Repository($params[0]))->select('*')->where($field, $value);
            return !$data->exists();
        }, 'This {field} is already used by another user');

        return self::validate($inputs, static::$rules, static::$messages);
    }
}
