<?php

declare(strict_types=1);

namespace App\Http\UseCases\User;

use App\Database\Models\User;

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
            ->send();
    }
}
