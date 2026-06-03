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
use App\Database\Models\User;
use App\Http\Middlewares\Authenticated;
use App\Http\Middlewares\CheckIfUserAdmin;
use App\Http\Middlewares\EmailVerified;
use App\Http\Validation\Validators\User\StoreValidator;
use App\Http\Validation\Validators\User\UpdateValidator;
use App\UseCases\User\StoreUseCase;
use Core\Database\Model;
use Core\Enums\HttpMethod;
use Core\Enums\RouteParameter;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;
use Core\Support\Alert;

class UserController extends Controller
{
    #[Route(
        methods: HttpMethod::GET,
        uri: '/dashboard/users',
        middlewares: [Authenticated::class, EmailVerified::class],
        name: 'users.index'
    )]
    public function index(): void
    {
        $this->render('dashboard.users.index', [
            'users' => User::findAllPaginate(),
        ]);
    }

    #[Route(
        methods: HttpMethod::GET,
        uri: '/dashboard/users/create',
        middlewares: [Authenticated::class, EmailVerified::class, CheckIfUserAdmin::class],
        name: 'users.create'
    )]
    public function create(): void
    {
        $this->render('dashboard.users.create', [
            'roles' => Role::findAll(),
        ]);
    }

    #[Route(
        methods: HttpMethod::POST,
        uri: '/dashboard/users',
        middlewares: [Authenticated::class, EmailVerified::class, CheckIfUserAdmin::class],
        name: 'users.store'
    )]
    public function store(StoreUseCase $useCase, StoreValidator $validator): void
    {
        $useCase->handle($validator->validated());
    }

    #[Route(
        methods: HttpMethod::GET,
        uri: '/dashboard/users/{user}/edit',
        middlewares: [Authenticated::class, EmailVerified::class, CheckIfUserAdmin::class],
        name: 'users.edit',
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function edit(Model $user): void
    {
        $this->render('dashboard.users.edit', [
            'user' => $user,
            'roles' => Role::findAll(),
        ]);
    }

    #[Route(
        methods: HttpMethod::PATCH,
        uri: '/dashboard/users/{user}',
        middlewares: [Authenticated::class, EmailVerified::class, CheckIfUserAdmin::class],
        name: 'users.update',
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function update(UpdateValidator $validator, Model $user): void
    {
        if (! $user->set($validator->validated())->save()) {
            Alert::toast('Failed to update user')->error();
        }

        Alert::toast('User updated')->success();
        $this->redirectBack();
    }

    #[Route(
        methods: HttpMethod::DELETE,
        uri: '/dashboard/users/{user}/delete',
        middlewares: [Authenticated::class, EmailVerified::class, CheckIfUserAdmin::class],
        name: 'users.delete',
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function delete(Model $user): void
    {
        if (! $user->delete()) {
            Alert::toast('Failed to delete user')->error();
        }

        Alert::toast('User deleted')->success();
        $this->redirectBack();
    }
}
