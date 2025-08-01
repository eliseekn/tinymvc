<?php

declare(strict_types=1);

/*
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/*
 * Write your custom helper functions here
 */

use App\Enums\UserRole;

if (! function_exists('is_admin')) {
    /*
     * Check if user is role admin
     */
    function is_admin(): bool
    {
        return auth()->belongsTo('roles')->get('name') === UserRole::ADMIN->value;
    }
}
