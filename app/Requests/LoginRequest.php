<?php

namespace App\Requests;

use Framework\Http\Request;
use GUMP;

/**
 * LoginRequest
 */
class LoginRequest extends Request
{
    /**
     * rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|valid_email',
            'password' => 'required'
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
            'email' => [
                'required' => 'Email address is required.',
                'valid_email' => 'Invalid email address format.'
            ],
            'password' => ['required' => 'Password is required.']
        ];
    }
    
    /**
     * validate
     *
     * @return void
     */
    public function validate()
    {
        $is_valid = GUMP::is_valid($this->postQuery(), $this->rules(), $this->messages());
        return $is_valid === true ? '' : $is_valid;
    } 
}