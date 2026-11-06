<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Middlewares\Authenticated;
use App\Http\Middlewares\EmailVerified;
use Core\Enums\HttpMethod;
use Core\Http\Response\BaseResponse;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

class DashboardController extends Controller
{
    #[Route(
        methods: HttpMethod::GET,
        uri: '/dashboard',
        middlewares: [Authenticated::class, EmailVerified::class],
        name: 'dashboard.index')
    ]
    public function __invoke(): BaseResponse
    {
        return $this->viewResponse('dashboard.index');
    }
}
