<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\PathItem;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityRequirement;

abstract class Docs
{
    public static function paths(): array
    {
        return [
            self::login(),
            self::logout(),
            self::register(),

            self::emailNotify(),
            self::emailVerify(),

            self::passwordNotify(),
            self::passwordReset(),
            self::passwordUpdate(),

            self::users(),
            self::user(),

            self::profile(),
            self::profileAvatar(),

            self::roles(),
        ];
    }

    private static function bearerAuth(): SecurityRequirement
    {
        return SecurityRequirement::create()->securityScheme('bearerAuth');
    }

    private static function jsonBody(Schema $schema): RequestBody
    {
        return RequestBody::create()
            ->required(true)
            ->content(
                MediaType::create()
                    ->mediaType(MediaType::MEDIA_TYPE_APPLICATION_JSON)
                    ->schema($schema)
            );
    }

    private static function jsonResponse(int $statusCode, string $description, Schema $schema): Response
    {
        return Response::create()
            ->statusCode($statusCode)
            ->description($description)
            ->content(
                MediaType::create()
                    ->mediaType(MediaType::MEDIA_TYPE_APPLICATION_JSON)
                    ->schema($schema)
            );
    }

    private static function errorResponse(int $statusCode, string $description): Response
    {
        return self::jsonResponse($statusCode, $description, Schema::object()->properties(
            Schema::string('status'),
            Schema::string('message'),
        ));
    }

    private static function login(): PathItem
    {
        return PathItem::create()
            ->route('/login')
            ->operations(
                Operation::post()
                    ->operationId('login')
                    ->tags('Auth')
                    ->requestBody(
                        self::jsonBody(
                            Schema::object()
                                ->properties(
                                    Schema::string('email'),
                                    Schema::string('password')->format(Schema::FORMAT_PASSWORD),
                                )
                                ->required('email', 'password')
                        )
                    )
                    ->responses(
                        self::jsonResponse(200, 'Login successful', Schema::object()->properties(
                            Schema::string('status'),
                            Schema::string('token'),
                            Schema::object('user'),
                        )),
                        self::errorResponse(401, 'Invalid credentials'),
                        self::errorResponse(422, 'Validation error'),
                    )
            );
    }

    private static function logout(): PathItem
    {
        return PathItem::create()
            ->route('/logout')
            ->operations(
                Operation::post()
                    ->operationId('logout')
                    ->tags('Auth')
                    ->security(self::bearerAuth())
                    ->responses(
                        self::jsonResponse(200, 'Logout successful', Schema::object()->properties(
                            Schema::string('status'),
                            Schema::string('message'),
                        )),
                        self::errorResponse(500, 'Server error'),
                    )
            );
    }

    private static function register(): PathItem
    {
        return PathItem::create()
            ->route('/register')
            ->operations(
                Operation::post()
                    ->operationId('register')
                    ->tags('Auth')
                    ->requestBody(
                        self::jsonBody(
                            Schema::object()
                                ->properties(
                                    Schema::string('name'),
                                    Schema::string('email'),
                                    Schema::string('password')->format(Schema::FORMAT_PASSWORD),
                                )
                                ->required('name', 'email', 'password')
                        )
                    )
                    ->responses(
                        self::jsonResponse(200, 'Account created', Schema::object()->properties(
                            Schema::string('status'),
                            Schema::string('message'),
                        )),
                        self::errorResponse(422, 'Validation error'),
                        self::errorResponse(500, 'Server error'),
                    )
            );
    }

    private static function emailNotify(): PathItem
    {
        return PathItem::create()
            ->route('/email/notify')
            ->operations(
                Operation::post()
                    ->operationId('emailNotify')
                    ->tags('Email Verification')
                    ->requestBody(
                        self::jsonBody(
                            Schema::object()
                                ->properties(
                                    Schema::string('email'),
                                )
                                ->required('email')
                        )
                    )
                    ->responses(
                        self::jsonResponse(200, 'Verification link sent', Schema::object()->properties(
                            Schema::string('status'),
                            Schema::string('message'),
                        )),
                        self::errorResponse(422, 'Validation error'),
                        self::errorResponse(500, 'Server error'),
                    )
            );
    }

    private static function emailVerify(): PathItem
    {
        return PathItem::create()
            ->route('/email/verify')
            ->operations(
                Operation::get()
                    ->operationId('emailVerify')
                    ->tags('Email Verification')
                    ->parameters(
                        Parameter::query()->name('token')->schema(Schema::string())->description('Verification token')->required(true),
                        Parameter::query()->name('email')->schema(Schema::string())->description('User email')->required(true),
                    )
                    ->responses(
                        self::jsonResponse(200, 'Email verified', Schema::object()->properties(
                            Schema::string('status'),
                            Schema::string('message'),
                        )),
                        self::errorResponse(400, 'Invalid or expired token'),
                    )
            );
    }

    private static function passwordNotify(): PathItem
    {
        return PathItem::create()
            ->route('/password/notify')
            ->operations(
                Operation::post()
                    ->operationId('passwordNotify')
                    ->tags('Password Reset')
                    ->requestBody(
                        self::jsonBody(
                            Schema::object()
                                ->properties(
                                    Schema::string('email'),
                                )
                                ->required('email')
                        )
                    )
                    ->responses(
                        self::jsonResponse(200, 'Password reset link sent', Schema::object()->properties(
                            Schema::string('status'),
                            Schema::string('message'),
                        )),
                        self::errorResponse(422, 'Validation error'),
                        self::errorResponse(500, 'Server error'),
                    )
            );
    }

    private static function passwordReset(): PathItem
    {
        return PathItem::create()
            ->route('/password/reset')
            ->operations(
                Operation::get()
                    ->operationId('passwordReset')
                    ->tags('Password Reset')
                    ->parameters(
                        Parameter::query()->name('token')->schema(Schema::string())->description('Reset token')->required(true),
                        Parameter::query()->name('email')->schema(Schema::string())->description('User email')->required(true),
                    )
                    ->responses(
                        self::jsonResponse(200, 'Token valid', Schema::object()->properties(
                            Schema::string('status'),
                            Schema::string('message'),
                        )),
                        self::errorResponse(400, 'Invalid or expired token'),
                    )
            );
    }

    private static function passwordUpdate(): PathItem
    {
        return PathItem::create()
            ->route('/password/update')
            ->operations(
                Operation::post()
                    ->operationId('passwordUpdate')
                    ->tags('Password Reset')
                    ->requestBody(
                        self::jsonBody(
                            Schema::object()
                                ->properties(
                                    Schema::string('email'),
                                    Schema::string('password')->format(Schema::FORMAT_PASSWORD),
                                )
                                ->required('email', 'password')
                        )
                    )
                    ->responses(
                        self::jsonResponse(200, 'Password updated', Schema::object()->properties(
                            Schema::string('status'),
                            Schema::string('message'),
                        )),
                        self::errorResponse(422, 'Validation error'),
                        self::errorResponse(500, 'Server error'),
                    )
            );
    }

    private static function users(): PathItem
    {
        return PathItem::create()
            ->route('/users')
            ->operations(
                Operation::get()
                    ->operationId('getUsersCollection')
                    ->tags('Users')
                    ->security(self::bearerAuth())
                    ->parameters(
                        Parameter::query()->name('page')->schema(Schema::integer())->description('Page number')->required(false),
                        Parameter::query()->name('per_page')->schema(Schema::integer())->description('Items per page')->required(false),
                        Parameter::query()->name('search')->schema(Schema::string())->description('Search term')->required(false),
                    )
                    ->responses(
                        Response::create()->statusCode(200)->description('OK'),
                    ),
                Operation::post()
                    ->operationId('storeUser')
                    ->tags('Users')
                    ->security(self::bearerAuth())
                    ->requestBody(
                        self::jsonBody(
                            Schema::object()
                                ->properties(
                                    Schema::string('name'),
                                    Schema::string('email'),
                                    Schema::string('password')->format(Schema::FORMAT_PASSWORD),
                                    Schema::integer('role_id'),
                                )
                                ->required('name', 'email', 'password', 'role_id')
                        )
                    )
                    ->responses(
                        self::jsonResponse(201, 'User created', Schema::object()->properties(
                            Schema::string('status'),
                            Schema::string('message'),
                            Schema::object('data'),
                        )),
                        self::errorResponse(500, 'Server error'),
                    )
            );
    }

    private static function user(): PathItem
    {
        $param = Parameter::path()
            ->name('user')
            ->required(true)
            ->schema(Schema::integer())
            ->description('User ID');

        return PathItem::create()
            ->route('/users/{user}')
            ->operations(
                Operation::get()
                    ->operationId('getUserItem')
                    ->tags('Users')
                    ->security(self::bearerAuth())
                    ->parameters($param)
                    ->responses(
                        Response::create()->statusCode(200)->description('OK'),
                        self::errorResponse(404, 'Not found'),
                    ),
                Operation::patch()
                    ->operationId('updateUser')
                    ->tags('Users')
                    ->security(self::bearerAuth())
                    ->parameters($param)
                    ->requestBody(
                        self::jsonBody(
                            Schema::object()->properties(
                                Schema::string('name'),
                                Schema::string('email'),
                                Schema::integer('role_id'),
                            )
                        )
                    )
                    ->responses(
                        self::jsonResponse(200, 'User updated', Schema::object()->properties(
                            Schema::string('status'),
                            Schema::string('message'),
                            Schema::object('data'),
                        )),
                        self::errorResponse(500, 'Server error'),
                    ),
                Operation::delete()
                    ->operationId('deleteUser')
                    ->tags('Users')
                    ->security(self::bearerAuth())
                    ->parameters($param)
                    ->responses(
                        self::jsonResponse(200, 'User deleted', Schema::object()->properties(
                            Schema::string('status'),
                            Schema::string('message'),
                        )),
                        self::errorResponse(404, 'Not found'),
                        self::errorResponse(500, 'Server error'),
                    )
            );
    }

    private static function profile(): PathItem
    {
        $param = Parameter::path()
            ->name('user')
            ->required(true)
            ->schema(Schema::integer())
            ->description('User ID');

        return PathItem::create()
            ->route('/account/{user}/profile')
            ->operations(
                Operation::patch()
                    ->operationId('updateProfile')
                    ->tags('Profile')
                    ->security(self::bearerAuth())
                    ->parameters($param)
                    ->requestBody(
                        self::jsonBody(
                            Schema::object()->properties(
                                Schema::string('name'),
                                Schema::string('email'),
                                Schema::integer('role_id'),
                            )
                        )
                    )
                    ->responses(
                        self::jsonResponse(200, 'Profile updated', Schema::object()->properties(
                            Schema::string('status'),
                            Schema::string('message'),
                            Schema::object('data'),
                        )),
                        self::errorResponse(404, 'Not found'),
                        self::errorResponse(500, 'Server error'),
                    )
            );
    }

    private static function profileAvatar(): PathItem
    {
        $param = Parameter::path()
            ->name('user')
            ->required(true)
            ->schema(Schema::integer())
            ->description('User ID');

        return PathItem::create()
            ->route('/account/{user}/profile/avatar')
            ->operations(
                Operation::delete()
                    ->operationId('deleteProfileAvatar')
                    ->tags('Profile')
                    ->security(self::bearerAuth())
                    ->parameters($param)
                    ->responses(
                        self::jsonResponse(200, 'Avatar deleted', Schema::object()->properties(
                            Schema::string('status'),
                            Schema::string('message'),
                        )),
                        self::errorResponse(404, 'Not found'),
                        self::errorResponse(500, 'Server error'),
                    )
            );
    }

    private static function roles(): PathItem
    {
        return PathItem::create()
            ->route('/roles')
            ->operations(
                Operation::get()
                    ->operationId('getRolesCollection')
                    ->tags('Roles')
                    ->responses(
                        Response::create()->statusCode(200)->description('OK'),
                    ),
            );
    }
}
