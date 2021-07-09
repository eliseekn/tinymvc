<?php

namespace App\Http\Validators;

use Core\Http\Validator;

class AuthRequest extends Validator
{
    /**
     * Validation rules
     */
    protected static $rules = [
        'email' => 'required|max_len,255',
        'password' => 'required|max_len,255'
    ];

    /**
     * Custom errors messages
     */
    protected static $messages = [
        //
    ];
}
