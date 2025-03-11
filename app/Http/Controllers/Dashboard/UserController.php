<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Database\Models\User;
use App\Http\UseCases\User\GetCollectionUseCase;
use App\Http\UseCases\User\StoreUseCase;
use App\Http\UseCases\User\UpdateUseCase;
use App\Http\Validation\Validators\User\StoreValidator;
use App\Http\Validation\Validators\User\UpdateValidator;
use Core\Enums\HttpMethod;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;
use Core\Support\Alert;

class UserController extends Controller
{
    #[Route(HttpMethod::GET, '/dashboard/users', ['auth', 'verified'], 'users.index')]
    public function index(GetCollectionUseCase $useCase): void
    {
        $useCase->handle($this->request->queries());
    }

    #[Route(HttpMethod::GET, '/dashboard/users/create', ['auth', 'verified', 'admin'], 'users.create')]
    public function create(): void
    {
        $this->render('dashboard.users.create');
    }

    #[Route(HttpMethod::POST, '/dashboard/users', ['auth', 'verified', 'admin'], 'users.store')]
    public function store(StoreUseCase $useCase, StoreValidator $validator): void
    {
        $useCase->handle($validator->inputs());
    }

    #[Route(HttpMethod::GET, '/dashboard/users/{id}/edit', ['auth', 'verified', 'admin'], 'users.edit', ['id' => 'num'])]
    public function edit(int $id): void
    {
        $user = User::find($id);

        if (! $user) {
            Alert::toast('User not found')->error();
            $this->redirectBack();
        }

        $this->render('dashboard.users.edit', ['user' => User::find($id)]);
    }

    #[Route(HttpMethod::PATCH, '/dashboard/users/{id}', ['auth', 'verified', 'admin'], 'users.update', ['id' => 'num'])]
    public function update(UpdateUseCase $useCase, UpdateValidator $validator, int $id): void
    {
        $user = User::find($id);

        if (! $user || ! $useCase->handle($validator->inputs(), $user->get('email'))) {
            Alert::toast('Failed to update user')->error();
        }

        Alert::toast('User updated')->success();
        $this->response->back()->send();
    }

    #[Route(HttpMethod::DELETE, '/dashboard/users/{id}/delete', ['auth', 'verified', 'admin'], 'users.delete', ['id' => 'num'])]
    public function delete(int $id): void
    {
        $user = User::find($id);

        if (! $user || ! $user->delete()) {
            Alert::toast('Failed to delete user')->error();
        }

        Alert::toast('User deleted')->success();
        $this->response->back()->send();
    }
}
