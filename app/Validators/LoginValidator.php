<?php

namespace App\Validators;

use GUMP;
use Framework\Http\Request;

class LoginValidator
{
    /**
     * set rules
     *
     * @return array
     */
    private static function rules(): array
    {
        return [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];
    }
    
    /**
     * set custom errors messages
     *
     * @return array
     */
    private static function messages(): array
    {
        return [
            //
        ];
    }
    
    /**
     * validate fields
     *
     * @return bool|array returns true or array of errors messages 
     */
    public static function validate()
    {
        return GUMP::is_valid(Request::getField(), self::rules(), self::messages());
    } 
}