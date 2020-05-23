<?php

namespace App\Middlewares;

use App\Requests\LoginRequest;
use Framework\Http\Redirect;

/**
 * LoginInputValidator
 * 
 * Validate login input fields
 */
class LoginInputValidator
{    
    /**
     * handle function
     *
     * @return void
     */
    public function handle()
    {
        $request = new LoginRequest();
        $error_messages = $request->validate();

        if (!empty($error_messages)) {
            Redirect::back()->withMessage('validator_errors', $error_messages);
        }
    }
}