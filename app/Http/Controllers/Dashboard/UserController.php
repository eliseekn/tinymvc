<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Dashboard;

use App\Database\Models\User;
use App\Http\UseCases\User\GetCollectionUseCase;
use App\Http\UseCases\User\StoreUseCase;
use App\Http\UseCases\User\UpdateUseCase;
use App\Http\Validators\User\StoreValidator;
use App\Http\Validators\User\UpdateValidator;
use Core\Enums\HttpMethod;
use Core\Routing\Attributes\Route;
use Core\Routing\Controller;
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
        $useCase->handle($validator->validated());
    }

    #[Route(HttpMethod::GET, '/dashboard/users/{id:int}/edit', ['auth', 'verified', 'admin'], 'users.edit')]
    public function edit(int $id): void
    {
        $this->render('dashboard.users.edit', ['user' => User::find($id)]);
    }

    #[Route(HttpMethod::PATCH, '/dashboard/users/{id:int}', ['auth', 'verified', 'admin'], 'users.update')]
    public function update(UpdateUseCase $useCase, UpdateValidator $validator, int $id): void
    {
        $email = User::find($id)->get('email');

        if (! $useCase->handle($validator->validated(), $email)) {
            Alert::toast('Failed to update user')->error();
        }

        Alert::toast('User updated')->success();
        $this->response->back()->send();
    }

    #[Route(HttpMethod::DELETE, '/dashboard/users/{id:int}/delete', ['auth', 'verified', 'admin'], 'users.delete')]
    public function delete(int $id): void
    {
        if (! User::find($id)->delete()) {
            Alert::toast('Failed to delete user')->error();
        }

        Alert::toast('User deleted')->success();
        $this->response->back()->send();
    }
}
