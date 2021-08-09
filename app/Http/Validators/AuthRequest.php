<?php

namespace App\Http\Validators;

use Core\Http\Validator;

class AuthRequest extends Validator
{
    /**
     * Validation rules
     */
    protected static $rules = [
        'email' => 'required|between_len,2;30',
        'password' => 'required|between_len,4;10'
    ];

    /**
     * Custom errors messages
     */
    protected static $messages = [
        //
    ];
}
