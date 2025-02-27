<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Testing;

use Core\Enums\HttpMethod;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;
use Symfony\Component\Panther\PantherTestCase;

/**
 * Manage browser tests.
 */
abstract class BrowserTestCase extends PantherTestCase
{
    public Client $client;

    public Crawler $crawler;

    protected function setUp(): void
    {
        $this->client = static::createPantherClient([
            'browser' => config('tests.browser'),
            'external_base_uri' => 'http://' . config('tests.host') . ':' . config('tests.port'),
        ]);

        parent::setUp();
    }

    public function visit(string $url): self
    {
        $this->crawler = $this->client->request(HttpMethod::GET, $url);

        return $this;
    }

    public function assertPageTitleEquals(string $expected): self
    {
        $this->assertEquals($expected, $this->client->getTitle());

        return $this;
    }
}
