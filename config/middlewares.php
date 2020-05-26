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

use Framework\Core\Middleware;

/**
 * Set routes paths
 */
Middleware::setName('CsrfTokenValidator', 'csrf_validator');
Middleware::setName('CheckSessionToLogin', 'auth_session');
Middleware::setName('CheckSessionToAdmin', 'admin_session');
Middleware::setName('LoginInputValidator', 'auth_validator');
Middleware::setName('CommentInputValidator', 'comment_validator');