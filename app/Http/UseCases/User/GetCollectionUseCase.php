<?php

declare(strict_types=1);

namespace App\Http\UseCases\User;

use App\Database\Models\User;

final class GetCollectionUseCase
{
    public function handle(): void
    {
        $query = request()->queries();

        response()
            ->view('dashboard.users.index', [
                'users' => User::findAllPaginate(
                    $query['perPage'] ?? 10,
                    $query['page'] ?? 1,
                    $query['search'] ?? null,
                ),
            ])
            ->send();
    }
}
