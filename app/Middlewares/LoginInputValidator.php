<?php

namespace App\Middlewares;

use Framework\Http\Request;
use Framework\Http\Redirect;
use Framework\Core\Middleware;

/**
 * LoginInputValidator
 * 
 * Login input fields validator
 */
class LoginInputValidator extends Middleware
{    
    /**
     * handle function
     *
     * @return void
     */
    public function handle()
    {
        $request = new Request();
        $csrf_token = $request->postQuery('csrf_token');

        if (!is_valid_csrf_token($csrf_token)) {
            Redirect::toRoute('login.page')->withMessage('', '');
        }
    }
}