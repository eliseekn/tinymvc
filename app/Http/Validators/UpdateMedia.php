<?php

namespace App\Http\Validators;

use GUMP;
use Framework\Http\Validator;
use Framework\Database\Repository;

class UpdateMedia extends Validator
{
    /**
     * rules
     * 
     * @var array
     */
    protected static $rules = [
        'filename' => 'required|max_len,255|unique,medias',
        'title' => 'max_len,255',
        'description' => 'max_len,255',
    ];

    /**
     * custom errors messages
     * 
     * @var array
     */
    protected static $messages = [
        //
    ];
    
    /**
     * register customs validators
     *
     * @return mixed
     */
    public static function register(): self
    {
        GUMP::add_validator('unique', function($field, array $input, array $params, $value) {
            $data = (new Repository($params[0]))->findBy($field, $value);
            return !$data->exists();
        }, "Record of {field} field already exists");

        return new self();
    }
}