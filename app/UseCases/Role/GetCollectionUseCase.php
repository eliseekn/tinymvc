<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Role;

use App\Database\Models\Role;
use Core\Cache\Cache;
use Core\Enums\HttpCode;

final class GetCollectionUseCase
{
    public function handle(): void
    {
        $data = Cache::read(
            'roles.dashboard',
            Role::findAllPaginate(),
            carbon()->addDay()->timestamp
        );

        response()
            ->view('dashboard.roles.index', ['roles' => $data])
            ->send(HttpCode::OK);
    }
}
