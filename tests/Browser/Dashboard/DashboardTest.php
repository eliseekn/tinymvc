<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Browser\Dashboard;

use Core\Testing\BrowserTestCase;
use Core\Testing\Traits\RefreshDatabase;
use Facebook\WebDriver\WebDriverSelect;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Fixtures;

class DashboardTest extends BrowserTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client->manage()->deleteAllCookies();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->refreshDatabase();
    }

    private function login(): void
    {
        $admin = Fixtures::createAdmin(['email_verified_at' => carbon()->now()]);

        $this
            ->visit('/login')
            ->assertPageTitleSame(__('form.login'));

        $this->client->submit(
            $this->crawler
                ->selectButton('Submit')
                ->form([
                    'email' => $admin->getEmail(),
                    'password' => 'P@ssw0rd',
                ])
        );

        $this->client->waitForElementToContain('h1', __('app.dashboard'));
        $this->crawler = $this->client->refreshCrawler();
    }

    private function selectPeriod(string $value): void
    {
        (new WebDriverSelect($this->findElement('#users-trends')))->selectByValue($value);
    }

    private function selectedPeriod(): string
    {
        return (new WebDriverSelect($this->findElement('#users-trends')))
            ->getFirstSelectedOption()
            ->getAttribute('value');
    }

    public function test_can_view_dashboard(): void
    {
        $this->login();

        $this->assertSelectorTextContains('h1', __('app.dashboard'));
    }

    public function test_guest_can_not_view_dashboard(): void
    {
        $this
            ->visit('/dashboard')
            ->assertPageTitleSame(__('form.login'));
    }

    #[DataProvider('periodProvider')]
    public function test_can_view_dashboard_for_each_period(string $period): void
    {
        $this->login();

        $this->visit('/dashboard?period='.$period);

        $this->assertSelectorTextContains('h1', __('app.dashboard'));
        $this->assertEquals($period, $this->selectedPeriod());
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

    public function test_can_change_period_using_selector(): void
    {
        $this->login();

        $this->selectPeriod('week');

        $this->client->wait()->until(
            fn () => str_contains($this->client->getCurrentURL(), 'period=week')
        );

        $this->crawler = $this->client->refreshCrawler();

        $this->assertSelectorTextContains('h1', __('app.dashboard'));
        $this->assertEquals('week', $this->selectedPeriod());
    }

    public function test_can_view_dashboard_for_custom_period_range(): void
    {
        $this->login();

        $start = carbon()->subDays(7)->format('Y-m-d');
        $end = carbon()->format('Y-m-d');

        $this->selectPeriod('custom');

        $this->client->waitForVisibility('.swal2-popup');

        $this->client->executeScript(
            "document.querySelector('#start_date').value = arguments[0]; document.querySelector('#end_date').value = arguments[1];",
            [$start, $end]
        );

        $this->findElement('.swal2-confirm')->click();

        $this->client->wait()->until(
            fn () => str_contains($this->client->getCurrentURL(), 'period='.$start.'~'.$end)
        );

        $this->crawler = $this->client->refreshCrawler();

        $this->assertSelectorTextContains('h1', __('app.dashboard'));
    }
}
