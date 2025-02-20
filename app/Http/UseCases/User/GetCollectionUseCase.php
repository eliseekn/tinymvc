<?php

declare(strict_types=1);

namespace App\Http\UseCases\User;

use App\Database\Models\User;
use Core\Support\UseCase;

class GetCollectionUseCase extends UseCase
{
    public function handle(array $query): void
    {
        $this
            ->response
            ->view('dashboard.users.index', [
                'users' => User::allPaginate(
                    $query['perPage'] ?? 10,
                    $query['page'] ?? 1,
                    $query['search'] ?? null,
                ),
            ])
            ->send();
    }
}
