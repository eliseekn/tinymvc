<?php

namespace App\Requests;

use Framework\Support\Validator;

class RegisterRequest extends Validator
{
    /**
     * rules
     * 
     * @var array
     */
    protected static $rules = [
        'name' => 'required',
        'email' => 'required|valid_email',
        'role' => 'required',
        'password' => 'required'
    ];

    /**
     * custom errors messages
     * 
     * @var array
     */
    protected static $error_messages = [];
}