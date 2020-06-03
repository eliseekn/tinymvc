<?php

namespace App\Validators;

use Framework\Http\Request;
use GUMP;

/**
 * ExampleValidator
 * 
 */
class ExampleValidator extends Request
{
    /**
     * rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'username' => 'required|alpha_numeric|max_len,8',
            'email' => 'required|valid_email',
            'password' => 'required|between_len,8;15'
        ];
    }
    
    /**
     * messages
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'username' => [
                'required' => 'Username is required.',
                'alpha_numeric' => 'Username must contains alphanumeric characters only.',
                'max_len' => 'Username cannot contains more than 8 characters.'
            ],
            'email' => [
                'required' => 'Email address is required.',
                'valid_email' => 'Invalid email address format.'
            ],
            'password' => [
                'required' => 'Password is required.',
                'between_len' => 'Password must contains between 8 and 15 characters.'
            ]
        ];
    }
    
    /**
     * validate
     *
     * @return void
     */
    public function validate()
    {
        $is_valid = GUMP::is_valid($this->getInput(), $this->rules(), $this->messages());
        return $is_valid === true ? '' : $is_valid;
    } 
}