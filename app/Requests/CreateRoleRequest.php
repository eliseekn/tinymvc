<?php

namespace App\Requests;

use Framework\Support\Validator;

class CreateRoleRequest extends Validator
{
    /**
     * rules
     * 
     * @var array
     */
    protected static $rules = [
        'title' => 'required'
    ];

    /**
     * custom errors messages
     * 
     * @var array
     */
    protected static $error_messages = [];
}