<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

/*
 * Twig configuration
 */

return [
    'disable_cache' => true,
    'debug' => true,

    'extensions' => [
        'functions' => [
            'is_admin' => 'is_admin',
        ],
        'filters' => [],
        'globals' => [],
    ],
];
