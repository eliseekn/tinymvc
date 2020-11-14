<?php

namespace App\Requests;

use Framework\Support\Validator;

class UpdateUser extends Validator
{
    /**
     * rules
     * 
     * @var array
     */
    protected static $rules = [
        'name' => 'required',
        'email' => 'required|valid_email',
        'phone' => 'required|numeric'
    ];

    /**
     * custom errors messages
     * 
     * @var array
     */
    protected static $error_messages = [];
}