<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Database\Models\Role;
use App\Http\Resources\RoleResource;
use Core\Enums\HttpCode;
use Core\Enums\HttpMethod;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

class RoleController extends Controller
{
    #[Route(HttpMethod::GET, '/api/v1/roles')]
    public function __invoke(): void
    {
        response()
            ->json(new RoleResource(Role::findAll())->handle())
            ->send(HttpCode::OK);
    }
}
