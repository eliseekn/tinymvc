<?php

namespace App\Requests;

use Framework\Support\Validator;

class NotificationRequest extends Validator
{
    /**
     * rules
     * 
     * @var array
     */
    protected static $rules = [
        'message' => 'required|string'
    ];

    /**
     * custom errors messages
     * 
     * @var array
     */
    protected static $error_messages = [];
}