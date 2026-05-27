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
use App\Http\Middlewares\Authenticated;
use App\Http\Middlewares\CheckIfUserAdmin;
use App\Http\Middlewares\EmailVerified;
use App\UseCases\Role\GetCollectionUseCase;
use App\UseCases\Role\StoreUseCase;
use App\Http\Validation\Validators\Role\StoreValidator;
use App\Http\Validation\Validators\Role\UpdateValidator;
use Core\Database\Model;
use Core\Enums\HttpMethod;
use Core\Enums\RouteParameter;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;
use Core\Support\Alert;

class RoleController extends Controller
{
    #[Route(
        methods: HttpMethod::GET,
        uri: '/dashboard/roles',
        middlewares: [Authenticated::class, EmailVerified::class],
        name: 'roles.index'
    )]
    public function index(GetCollectionUseCase $useCase): void
    {
        $useCase->handle();
    }

    #[Route(
        methods: HttpMethod::GET,
        uri: '/dashboard/roles/create',
        middlewares: [Authenticated::class, EmailVerified::class, CheckIfUserAdmin::class],
        name: 'roles.create'
    )]
    public function create(): void
    {
        $this->render('dashboard.roles.create', [
            'roles' => Role::findAll(),
        ]);
    }

    #[Route(
        methods: HttpMethod::POST,
        uri: '/dashboard/roles',
        middlewares: [Authenticated::class, EmailVerified::class, CheckIfUserAdmin::class],
        name: 'roles.store'
    )]
    public function store(StoreUseCase $useCase, StoreValidator $validator): void
    {
        $useCase->handle($validator->inputs());
    }

    #[Route(
        methods: HttpMethod::GET,
        uri: '/dashboard/roles/{role}/edit',
        middlewares: [Authenticated::class, EmailVerified::class, CheckIfUserAdmin::class],
        name: 'roles.edit',
        parameters: ['role' => RouteParameter::NUMBER],
        bindings: ['role' => ['roles', 'id']]
    )]
    public function edit(Model $role): void
    {
        $this->render('dashboard.roles.edit', [
            'role' => $role,
            'roles' => Role::findAll(),
        ]);
    }

    #[Route(
        methods: HttpMethod::PATCH,
        uri: '/dashboard/roles/{role}',
        middlewares: [Authenticated::class, EmailVerified::class, CheckIfUserAdmin::class],
        name: 'roles.update',
        parameters: ['role' => RouteParameter::NUMBER],
        bindings: ['role' => ['roles', 'id']]
    )]
    public function update(UpdateValidator $validator, Model $role): void
    {
        if (! $role->set($validator->inputs())->save()) {
            Alert::toast('Failed to update role')->error();
        }

        Alert::toast('Role updated')->success();
        $this->redirectBack();
    }

    #[Route(
        methods: HttpMethod::DELETE,
        uri: '/dashboard/roles/{role}/delete',
        middlewares: [Authenticated::class, EmailVerified::class, CheckIfUserAdmin::class],
        name: 'roles.delete',
        parameters: ['role' => RouteParameter::NUMBER],
        bindings: ['role' => ['roles', 'id']]
    )]
    public function delete(Model $role): void
    {
        if (! $role->delete()) {
            Alert::toast('Failed to delete role')->error();
        }

        Alert::toast('Role deleted')->success();
        $this->redirectBack();
    }
}
