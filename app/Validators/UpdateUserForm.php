<?php

namespace App\Validators;

use Framework\Support\FormValidation;

class UpdateUserForm extends FormValidation
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