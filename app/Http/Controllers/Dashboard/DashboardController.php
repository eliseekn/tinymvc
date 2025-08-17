<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Database\Models\User;
use App\Enums\UserRole;
use Core\Database\Metrics\Enums\Period;
use Core\Database\Metrics\Metrics;
use Core\Database\QueryBuilder;
use Core\Enums\HttpMethod;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

class DashboardController extends Controller
{
    #[Route(
        methods: HttpMethod::GET,
        uri: '/dashboard',
        middlewares: ['auth', 'verified'],
        name: 'dashboard.index')
    ]
    public function __invoke(User $user): void
    {
        $period = request()->queries('period', 'day');

        if (str_contains($period, '~')) {
            $period = explode('~', $period, 2);
        }

        $this->render('dashboard.index', [
            'totalUsers' => $this->metrics((new User)->metrics(), $period),
            'totalUsersToday' => (new User)->metrics()->countByDay(count: 1)->metricsWithVariations(1, Period::DAY->value),
            'usersTrends' => $this->trends((new User)->metrics()->fillMissingData(), $period),
            'usersRolesTrends' => $this->trendsByRoles(
                $user
                    ->metrics()
                    ->subQuery(function (QueryBuilder $q) {
                        $q->addJoin('roles', 'users.role_id', '=', 'roles.id');
                    })
                    ->labelColumn('roles.name')
                    ->fillMissingData(missingDataLabels: UserRole::values()),
                $period),
        ]);
    }

    private function metrics(Metrics $metrics, string|array $period): mixed
    {
        if (is_array($period)) {
            return $metrics
                ->countBetween($period)
                ->metrics();
        }

        return match ($period) {
            'day' => $metrics->countByDay()->metrics(),
            'week' => $metrics->countByWeek()->metrics(),
            'last_week' => $metrics->countFrom(carbon()->subWeek()->startOfWeek()->format('Y-m-d'))->metrics(),
            'quater_year' => $metrics->countByMonth(count: 4)->metrics(),
            'half_year' => $metrics->countByMonth(count: 6)->metrics(),
            'month' => $metrics->countByMonth()->metrics(),
            'last_month' => $metrics->countFrom(carbon()->subMonth()->startOfMonth()->format('Y-m-d'))->metrics(),
            'year' => $metrics->countByYear(count: 5)->metrics(),
            'last_year' => $metrics->countFrom(carbon()->subYear()->startOfYear()->format('Y-m-d'))->metrics(),
            default => 0
        };
    }

    private function trends(Metrics $metrics, string|array $period): array
    {
        if (is_array($period)) {
            return $metrics
                ->countBetween($period)
                ->trends();
        }

        return match ($period) {
            'day' => $metrics->countByDay()->trends(),
            'last_week' => $metrics->countFrom(carbon()->subWeek()->startOfWeek()->format('Y-m-d'))->trends(),
            'week' => $metrics->countByWeek()->trends(),
            'quater_year' => $metrics->countByMonth(count: 4)->trends(),
            'half_year' => $metrics->countByMonth(count: 6)->trends(),
            'month' => $metrics->countByMonth()->trends(),
            'last_month' => $metrics->countFrom(carbon()->subMonth()->startOfMonth()->format('Y-m-d'))->trends(),
            'year' => $metrics->countByYear(count: 5)->trends(),
            'last_year' => $metrics->countFrom(carbon()->subYear()->startOfYear()->format('Y-m-d'))->trends(),
            default => []
        };
    }

    private function trendsByRoles(Metrics $metrics, string|array $period): array
    {
        if (is_array($period)) {
            return $metrics
                ->countBetween($period)
                ->trends(true);
        }

        return match ($period) {
            'day' => $metrics->countByDay()->trends(true),
            'last_week' => $metrics->countFrom(carbon()->subWeek()->startOfWeek()->format('Y-m-d'))->trends(true),
            'week' => $metrics->countByWeek()->trends(true),
            'quater_year' => $metrics->countByMonth(count: 4)->trends(true),
            'half_year' => $metrics->countByMonth(count: 6)->trends(true),
            'month' => $metrics->countByMonth()->trends(true),
            'last_month' => $metrics->countFrom(carbon()->subMonth()->startOfMonth()->format('Y-m-d'))->trends(true),
            'year' => $metrics->countByYear(count: 5)->trends(true),
            'last_year' => $metrics->countFrom(carbon()->subYear()->startOfYear()->format('Y-m-d'))->trends(true),
            default => []
        };
    }
}
