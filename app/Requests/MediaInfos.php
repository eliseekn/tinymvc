<?php

namespace App\Requests;

use Framework\Support\Validator;

class MediaInfos extends Validator
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
    protected static $error_messages = [
        //
    ];
}