<?php

namespace App\Requests;

use Framework\Support\Validator;

class RegisterUser extends Validator
{
    /**
     * rules
     * 
     * @var array
     */
    protected static $rules = [
        'name' => 'required|alpha_space',
        'email' => 'required|valid_email',
        'phone' => 'required|numeric',
        'password' => 'required'
    ];

    /**
     * custom errors messages
     * 
     * @var array
     */
    protected static $error_messages = [];
}