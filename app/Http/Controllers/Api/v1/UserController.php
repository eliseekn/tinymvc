<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\UseCases\Api\v1\User\DeleteUseCase;
use App\Http\UseCases\Api\v1\User\GetCollectionUseCase;
use App\Http\UseCases\Api\v1\User\GetItemUseCase;
use App\Http\UseCases\Api\v1\User\StoreUseCase;
use App\Http\UseCases\Api\v1\User\UpdateUseCase;
use App\Http\Validation\Validators\User\StoreValidator;
use App\Http\Validation\Validators\User\UpdateValidator;
use Core\Database\Model;
use Core\Enums\HttpMethod;
use Core\Enums\RouteParameter;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

class UserController extends Controller
{
    #[Route(HttpMethod::GET, '/api/v1/users', ['api'])]
    public function index(GetCollectionUseCase $useCase): void
    {
        $useCase->handle();
    }

    #[Route(
        methods: HttpMethod::GET,
        uri: '/api/v1/users/{user}',
        middlewares: ['api'],
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function show(GetItemUseCase $useCase, Model $user): void
    {
        $useCase->handle($user);
    }

    #[Route(HttpMethod::POST, '/api/v1/users', ['api', 'admin'])]
    public function store(StoreUseCase $useCase, StoreValidator $validator): void
    {
        $useCase->handle($validator->inputs());
    }

    #[Route(
        methods: [HttpMethod::PATCH, HttpMethod::PUT],
        uri: '/api/v1/users/{user}',
        middlewares: ['api', 'admin'],
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function update(UpdateUseCase $useCase, UpdateValidator $validator, Model $user): void
    {
        $useCase->handle($validator->inputs(), $user);
    }

    #[Route(
        methods: HttpMethod::DELETE,
        uri: '/api/v1/users/{user}',
        middlewares: ['api', 'admin'],
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function delete(DeleteUseCase $useCase, Model $user): void
    {
        $useCase->handle($user);
    }
}
