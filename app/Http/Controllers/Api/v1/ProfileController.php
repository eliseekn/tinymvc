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
use App\Http\UseCases\Api\v1\User\DeleteUseCase;
use App\Http\UseCases\Api\v1\User\UpdateUseCase;
use App\Http\Validation\Validators\User\UpdateValidator;
use App\Policies\ProfilePolicy;
use Core\Database\Model;
use Core\Enums\HttpMethod;
use Core\Enums\RouteName;
use Core\Enums\RouteParameter;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;
use Core\Policy\Policy;

class ProfileController extends Controller
{
    #[Route(
        methods: [HttpMethod::PATCH, HttpMethod::PUT],
        uri: '/api/v1/account/{user}/profile',
        middlewares: [ApiAuth::class, CheckIfUserAdmin::class],
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function update(UpdateUseCase $useCase, UpdateValidator $validator, Model $user): void
    {
        Policy::authorize(new ProfilePolicy, RouteName::UPDATE, $user);

        $useCase->handle($validator->inputs());
    }

    #[Route(
        methods: HttpMethod::DELETE,
        uri: '/api/v1/account/{user}/profile/avatar',
        middlewares: [ApiAuth::class, CheckIfUserAdmin::class],
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function delete(DeleteUseCase $useCase, Model $user): void
    {
        Policy::authorize(new ProfilePolicy, RouteName::DELETE, $user);

        $useCase->handle($user);
    }
}
