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
        'name' => 'required|alpha_space|max_len,255',
        'email' => 'required|valid_email|max_len,255',
        'phone' => 'required|numeric|max_len,255',
        'password' => 'required|max_len,255'
    ];

    /**
     * custom errors messages
     * 
     * @var array
     */
    protected static $error_messages = [];
}