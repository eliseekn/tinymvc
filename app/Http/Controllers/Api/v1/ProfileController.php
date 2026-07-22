<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Database\Entities\User;
use App\Http\Middlewares\ApiAuth;
use App\Http\Middlewares\CheckIfUserAdmin;
use App\Http\Resources\UserResource;
use App\Http\Validation\Validators\UpdateProfileValidator;
use App\UseCases\Api\v1\User\DeleteAvatarUseCase;
use App\UseCases\Api\v1\User\UpdateUseCase;
use Core\Enums\HttpMethod;
use Core\Enums\RouteParameter;
use Core\Http\Response\BaseResponse;
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
    public function update(UpdateUseCase $useCase, UpdateProfileValidator $validator, User $user): BaseResponse
    {
        return $this->processUpdatedProfile($user, $useCase->handle($validator->validated(), $user));
    }

    #[Route(
        methods: HttpMethod::DELETE,
        uri: '/api/v1/account/{user}/profile/avatar',
        middlewares: [ApiAuth::class, CheckIfUserAdmin::class],
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function delete(DeleteAvatarUseCase $useCase, User $user): BaseResponse
    {
        return $this->processUpdatedProfile($user, $useCase->handle($user));
    }

    protected function processUpdatedProfile(User $user, bool $updated): BaseResponse
    {
        if (! $updated) {
            return $this->errorJsonResponse('Failed to update profile');
        }

        return $this->successJsonResponse([
            'message' => 'Profile updated',
            'data' => new UserResource($user)->handle(),
        ]);
    }
}
