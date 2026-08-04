<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Feature\Dashboard;

use Core\Testing\FeatureTestCase;
use Core\Testing\Traits\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Fixtures;

class DashboardTest extends FeatureTestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->refreshDatabase();
    }

    public function test_can_view_dashboard(): void
    {
        $this
            ->auth(Fixtures::createUser())
            ->get('/dashboard')
            ->assertHttpStatusOk();
    }

    public function test_guest_can_not_view_dashboard(): void
    {
        $this
            ->get('/dashboard')
            ->assertHttpStatusFound()
            ->assertRedirectedToUrl('/login');
    }

    #[DataProvider('periodProvider')]
    public function test_can_view_dashboard_for_each_period(string $period): void
    {
        $this
            ->auth(Fixtures::createUser())
            ->get('/dashboard?period='.$period)
            ->assertHttpStatusOk();
    }

    public static function periodProvider(): array
    {
        return [
            'day' => ['day'],
            'week' => ['week'],
            'last_week' => ['last_week'],
            'month' => ['month'],
            'last_month' => ['last_month'],
            'quater_year' => ['quater_year'],
            'half_year' => ['half_year'],
            'year' => ['year'],
            'last_year' => ['last_year'],
        ];
    }

    public function test_can_view_dashboard_for_custom_period_range(): void
    {
        $start = carbon()->subDays(7)->format('Y-m-d');
        $end = carbon()->format('Y-m-d');

        $this
            ->auth(Fixtures::createUser())
            ->get('/dashboard?period='.$start.'~'.$end)
            ->assertHttpStatusOk();
    }
}
