<?php

namespace App\Http\Validators;

use Framework\Http\Validator;

class UpdateMedia extends Validator
{
    /**
     * rules
     * 
     * @var array
     */
    protected static $rules = [
        'filename' => 'required|max_len,255',
        'title' => 'max_len,255',
        'description' => 'max_len,255',
    ];

    /**
     * custom errors messages
     * 
     * @var array
     */
    protected static $messages = [];
}