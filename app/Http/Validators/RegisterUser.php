<?php

namespace App\Http\Validators;

use GUMP;
use Core\Http\Validator;
use Core\Database\Repository;

class RegisterUser extends Validator
{
    /**
     * rules
     * 
     * @var array
     */
    protected static $rules = [
        'name' => 'required|max_len,255',
        'email' => 'required|valid_email|max_len,255|unique,users',
        'password' => 'required|max_len,255'
    ];

    /**
     * custom errors messages
     * 
     * @var array
     */
    protected static $messages = [
        //
    ];
    
    /**
     * register customs validators
     *
     * @return mixed
     */
    public static function register(): self
    {
        GUMP::add_validator('unique', function($field, array $input, array $params, $value) {
            $data = (new Repository($params[0]))->findWhere($field, $value);
            return !$data->exists();
        }, "Record of {field} field already exists");

        return new self();
    }
}
