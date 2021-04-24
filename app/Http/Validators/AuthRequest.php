<?php

namespace App\Http\Validators;

use GUMP;
use Framework\Http\Validator;

class AuthRequest extends Validator
{
    /**
     * rules
     * 
     * @var array
     */
    protected static $rules = [
        'email' => 'required|max_len,255',
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
        GUMP::add_validator('name_of_rule', function($field, array $input, array $params, $value) {
            return true;
        }, '');

        return new self();
    }
}
