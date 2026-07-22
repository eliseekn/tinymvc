<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

/*
 * Errors configuration
 */

return [
    'display' => true,
    'log' => true,

    'views' => [
        '401' => 'errors'.DIRECTORY_SEPARATOR.'401',
        '403' => 'errors'.DIRECTORY_SEPARATOR.'403',
        '404' => 'errors'.DIRECTORY_SEPARATOR.'404',
    ],
];
