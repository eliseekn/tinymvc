<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Database\Models\Role;
use App\Http\UseCases\User\GetCollectionUseCase;
use App\Http\UseCases\User\StoreUseCase;
use App\Http\UseCases\User\UpdateUseCase;
use App\Http\Validation\Validators\User\StoreValidator;
use App\Http\Validation\Validators\User\UpdateValidator;
use Core\Database\Model;
use Core\Enums\HttpMethod;
use Core\Enums\RouteParameter;
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
        $this->render('dashboard.users.create', [
            'roles' => Role::findAll(),
        ]);
    }

    #[Route(HttpMethod::POST, '/dashboard/users', ['auth', 'verified', 'admin'], 'users.store')]
    public function store(StoreUseCase $useCase, StoreValidator $validator): void
    {
        $useCase->handle($validator->inputs());
    }

    #[Route(
        methods: HttpMethod::GET,
        uri: '/dashboard/users/{user}/edit',
        middlewares: ['auth', 'verified', 'admin'],
        name: 'users.edit',
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function edit(?Model $user = null): void
    {
        if (! $user) {
            Alert::toast('User not found')->error();
            $this->redirectBack();
        }

        $this->render('dashboard.users.edit', [
            'user' => $user,
            'roles' => Role::findAll(),
        ]);
    }

    #[Route(
        methods: HttpMethod::PATCH,
        uri: '/dashboard/users/{user}',
        middlewares: ['auth', 'verified', 'admin'],
        name: 'users.update',
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function update(UpdateUseCase $useCase, UpdateValidator $validator, ?Model $user = null): void
    {
        if (! $user || ! $useCase->handle($validator->inputs(), $user->get('email'))) {
            Alert::toast('Failed to update user')->error();
        }

        Alert::toast('User updated')->success();
        $this->response->back()->send();
    }

    #[Route(
        methods: HttpMethod::DELETE,
        uri: '/dashboard/users/{user}/delete',
        middlewares: ['auth', 'verified', 'admin'],
        name: 'users.delete',
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function delete(?Model $user = null): void
    {
        if (! $user || ! $user->delete()) {
            Alert::toast('Failed to delete user')->error();
        }

        Alert::toast('User deleted')->success();
        $this->response->back()->send();
    }
}
