<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\Middlewares\ApiAuth;
use App\Http\Middlewares\CheckIfUserAdmin;
use App\Http\Validation\Validators\UpdateProfileValidator;
use App\UseCases\Api\v1\User\DeleteAvatarUseCase;
use App\UseCases\Api\v1\User\UpdateUseCase;
use Core\Database\Model;
use Core\Enums\HttpMethod;
use Core\Enums\RouteParameter;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

class ProfileController extends Controller
{
    #[Route(
        methods: [HttpMethod::PATCH, HttpMethod::PUT],
        uri: '/api/v1/account/{user}/profile',
        middlewares: [ApiAuth::class, CheckIfUserAdmin::class],
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function update(UpdateUseCase $useCase, UpdateProfileValidator $validator, Model $user): void
    {
        $useCase->handle($validator->validated(), $user);
    }

    #[Route(
        methods: HttpMethod::DELETE,
        uri: '/api/v1/account/{user}/profile/avatar',
        middlewares: [ApiAuth::class, CheckIfUserAdmin::class],
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function delete(DeleteAvatarUseCase $useCase, Model $user): void
    {
        $useCase->handle($user);
    }
}
