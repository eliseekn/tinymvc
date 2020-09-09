<?php

namespace App\Requests;

use Framework\Support\Validator;

class UpdateUserRequest extends Validator
{
    /**
     * rules
     * 
     * @var array
     */
    protected static $rules = [
        'name' => 'required',
        'email' => 'required|valid_email'
    ];

    /**
     * custom errors messages
     * 
     * @var array
     */
    protected static $error_messages = [];
}