<?php

namespace App\Requests;

use Framework\Support\Validator;

class AuthRequest extends Validator
{
    /**
     * rules
     * 
     * @var array
     */
    protected static $rules = [
        'email' => 'required|valid_email',
        'password' => 'required'
    ];

    /**
     * custom errors messages
     * 
     * @var array
     */
    protected static $error_messages = [];
}