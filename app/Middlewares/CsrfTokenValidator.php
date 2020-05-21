<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace App\Middlewares;

use Framework\Core\Controller;
use Framework\Http\Middleware;
use Framework\Http\Request;

/**
 * CsrfTokenValidator
 * 
 * Middleware for validate csrf token
 */
class CsrfTokenValidator extends Middleware
{    
    /**
     * handle
     *
     * @return void
     */
    public function handle()
    {
        $request = new Request();
        $csrf_token = $request->postQuery('csrf_token');

        if (!is_valid_csrf_token($csrf_token)) {
            $this->next();
        }

        Controller::redirectToRoute('login');
    }
}