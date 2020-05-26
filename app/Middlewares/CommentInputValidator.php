<?php

namespace App\Middlewares;

use App\Requests\CommentRequest;
use Framework\Http\Redirect;

/**
 * CommentInputValidator
 * 
 * Validate comment input fields
 */
class CommentInputValidator
{    
    /**
     * handle function
     *
     * @return void
     */
    public function handle()
    {
        $request = new CommentRequest();
        $error_messages = $request->validate();

        if (!empty($error_messages)) {
            Redirect::back()->withMessage('validator_errors', $error_messages);
        }
    }
}