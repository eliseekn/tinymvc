<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\UseCases\Role;

use Core\Database\Model;
use Core\Support\Alert;

final class UpdateUseCase
{
    public function handle(Model $role, array $data): void
    {
        if (! $role->update($data)) {
            Alert::toast('Failed to update role')->error();
            response()->back()->send();
        }

        Alert::toast('Role updated')->success();
        response()->back()->send();
    }
}
