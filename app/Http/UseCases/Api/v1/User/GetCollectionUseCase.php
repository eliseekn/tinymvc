<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\UseCases\Api\v1\User;

use App\Database\Models\User;
use App\Http\Resources\UserResource;
use Core\Enums\HttpCode;

final class GetCollectionUseCase
{
    public function handle(): void
    {
        $data = User::findAllPaginate(
            (int) request()->queries('per_page', 10),
            (int) request()->queries('page', 1),
            request()->queries('search')
        );

        response()
            ->json(new UserResource($data)->handle())
            ->send(HttpCode::OK);
    }
}
