<?php

namespace App\Http\Validators;

use Core\Http\Validator;

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
}
