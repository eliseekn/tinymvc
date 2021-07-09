<?php

namespace App\Http\Validators;

use GUMP;
use Core\Http\Validator;
use Core\Database\Repository;

class RegisterUser extends Validator
{
    /**
     * Validation rules
     */
    protected static $rules = [
        'name' => 'required|between_len,10;30',
        'email' => 'required|valid_email|max_len,30|unique,users',
        'password' => 'required|between_len,8;10'
    ];

    /**
     * Custom errors messages
     */
    protected static $messages = [
        //
    ];
    
    /**
     * Register custom validators
     */
    public static function register(): self
    {
        GUMP::add_validator('unique', function($field, array $input, array $params, $value) {
            $data = (new Repository($params[0]))->where($field, $value);
            return !$data->exists();
        }, "Record of {field} field already exists");

        return new self();
    }
}
