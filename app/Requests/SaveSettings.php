<?php

namespace App\Requests;

use Framework\Support\Validator;

class SaveSettings extends Validator
{
    /**
     * rules
     * 
     * @var array
     */
    protected static $rules = [
        'name' => 'required',
        'email' => 'required|valid_email',
        'company' => 'required',
        'phone' => 'required|numeric'
    ];

    /**
     * custom errors messages
     * 
     * @var array
     */
    protected static $error_messages = [
        //
    ];
}