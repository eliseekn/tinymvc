<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

/**
 * Email configuration
 */

define('EMAIL', [
    'transport' => 'smtp', //or sendmail
    'host' => 'localhost',
    'port' => 25,
    'username' => '',
    'password' => '',
    'from' => 'admin@mail.com',
    'name' => 'Admin'
]);
