<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\UseCases\Api\v1\User;

use App\Database\Models\User;
use Core\Enums\HttpCode;
use Core\Support\UseCase;

class GetCollectionUseCase extends UseCase
{
    public function handle(array $query): void
    {
        $this
            ->response
            ->json(
                User::allPaginate(
                    $query['perPage'] ?? 10,
                    $query['page'] ?? 1,
                    $query['search'] ?? null,
                )->getItemsAsArray()
            )
            ->send(HttpCode::OK);
    }
}
