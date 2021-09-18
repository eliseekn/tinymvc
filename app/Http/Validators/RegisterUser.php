<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Validators;

use Core\Http\Validator\GUMPValidator as Validator;
use Core\Database\Repository;

class RegisterUser extends Validator
{
    /**
     * Validation rules
     */
    protected static $rules = [
        'name' => 'required|min_len,2',
        'email' => 'required|valid_email|min_len,5|unique,users',
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
        self::addRule('unique', function($field, array $input, array $params, $value) {
            $data = (new Repository($params[0]))->select('*')->where($field, $value);
            return !$data->exists();
        }, 'This {field} is already used by another user');

        return self::validate($inputs, static::$rules, static::$messages);
    }
}
