<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Dashboard;

use App\Database\Models\User;
use Core\Enums\HttpMethod;
use Core\Routing\Attributes\Route;
use Core\Routing\Controller;

class DashboardController extends Controller
{
    #[Route(HttpMethod::GET, '/dashboard', ['auth', 'verified'], 'dashboard.index')]
    public function __invoke(): void
    {
        $totalUsers = (new User)->count();
        $usersTrend = (new User)
            ->metrics()
            ->count()
            ->byYear()
            ->trends();

        $this->render('dashboard.index', compact('totalUsers', 'usersTrend'));
    }
}
