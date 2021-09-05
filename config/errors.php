<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Errors configuration
 */

return [
    'display' => true,
    'log' => true,

    'views' => [
        '404' => 'errors' . DS . '404',
        '500' => 'errors' . DS . '500'
    ]
];
