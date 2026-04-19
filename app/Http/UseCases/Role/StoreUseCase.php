<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\UseCases\Role;

use App\Database\Models\Role;
use Core\Support\Alert;

final class StoreUseCase
{
    public function handle(array $data): void
    {
        if (! Role::factory()->create($data)) {
            Alert::toast('Failed to create role')->error();
        } else {
            Alert::toast('Role created')->success();
        }

        response()->back()->send();
    }
}
