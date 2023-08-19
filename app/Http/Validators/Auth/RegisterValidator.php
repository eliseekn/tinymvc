<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Validators\Auth;

use Core\Http\Validator\Validator;

class RegisterValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max_len,255',
            'email' => 'required|valid_email|max_len,255|unique,users',
            'password' => 'required|max_len,255'
        ];
    }

    /**
     * Custom errors messages
     */
    public function messages(): array
    {
        return [];
    }
}
