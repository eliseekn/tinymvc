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

/**
 * Email configuration
 */

define('MAILER', [
    'transport' => 'smtp', //or sendmail
    'host' => 'localhost',
    'port' => 25,
    'username' => '',
    'password' => ''
]);
