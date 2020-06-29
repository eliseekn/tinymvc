<?php

namespace App\Validators;

use Framework\Support\FormValidation;

class RegisterForm extends FormValidation
{
    /**
     * rules
     * 
     * @var array
     */
    protected static $rules = [
        'name' => 'required',
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