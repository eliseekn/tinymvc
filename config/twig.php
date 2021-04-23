<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Twig configuration
 */

$config = [
    'disable_cache' => true,
    'extensions' => [
        'functions' => [],
        'filters' => [],
        'globals' => [
            'USER_ROLE' => \App\Database\Repositories\Roles::ROLE,
            'MEDIA_TYPE' => \App\Database\Repositories\Medias::TYPE
        ]
    ]
];
