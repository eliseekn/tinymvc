<?php

namespace App\Requests;

use Framework\Http\Request;
use GUMP;

/**
 * CommentRequest
 */
class CommentRequest extends Request
{
    /**
     * rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'author' => 'required|valid_email',
            'content' => 'required'
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
            'author' => [
                'required' => 'Email address is required.',
                'valid_email' => 'Invalid email address format.'
            ],
            'content' => ['required' => 'Comment text is required.']
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