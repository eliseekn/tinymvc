<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\UseCases\User;

use App\Database\Models\User;
use Core\Enums\HttpCode;

final class GetCollectionUseCase
{
    public function handle(): void
    {
        response()
            ->view('dashboard.users.index', [
                'users' => User::findAllPaginate(
                    (int) request()->queries('perPage', 10),
                    (int) request()->queries('page', 1),
                    request()->queries('search')
                ),
            ])
            ->send(HttpCode::OK);
    }
}
