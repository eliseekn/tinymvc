<?php

namespace App\Validators;

use GUMP;
use Framework\Http\Request;

class ExampleValidator
{
    /**
     * rules
     *
     * @return array
     */
    private static function rules(): array
    {
        return [
            //
        ];
    }
    
    /**
     * errors messages
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
     * validate request inputs
     *
     * @return string|array returns empty sting or array of errors messages 
     */
    public static function validate()
    {
        $is_valid = GUMP::is_valid(Request::getInput(), self::rules(), self::messages());
        return $is_valid === true ? '' : $is_valid;
    } 
}