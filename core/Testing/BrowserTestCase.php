<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Testing;

use Core\Enums\HttpMethod;
use Core\Testing\Traits\DatabaseTestCase;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\WebDriverElement;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;
use Symfony\Component\Panther\PantherTestCaseTrait;

/**
 * Manage browser tests.
 *
 * @link https://symfony.com/doc/current/testing/end_to_end.html
 */
abstract class BrowserTestCase extends TestCase
{
    use DatabaseTestCase;
    use PantherTestCaseTrait;

    public Client $client;

    public Crawler $crawler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createPantherClient([
            'browser' => config('testing.browser.name'),
            'external_base_uri' => config('testing.url.protocol').config('testing.url.host').':'.config('testing.url.port'),
        ]);

        $this->client->manage()->window()->setSize(
            new WebDriverDimension(1500, 2000)
        );
    }

    public function url(string $uri): string
    {
        return config('testing.url.protocol').config('tests.url.host').':'.config('tests.url.port').'/'.ltrim($uri, '/');
    }

    public function visit(string $url, string $method = HttpMethod::GET): self
    {
        $this->crawler = $this->client->request($method, $url);

        return $this;
    }

    public function takeScreenshot(): self
    {
        $saveAs = storage(config('testing.browser.screenshots_dir'))
            ->file(uniqid('screenshot_', true).'.png');

        $this->client->takeScreenshot($saveAs);

        return $this;
    }

    public function getSelectorText(string $selector): string
    {
        return $this->crawler->filter($selector)->text();
    }

    /**
     * @throws NoSuchElementException
     */
    public function findElement(string $element): WebDriverElement
    {
        $element = trim($element);

        $webDriver = $element === '' || $element[0] !== '/'
            ? WebDriverBy::cssSelector($element)
            : WebDriverBy::xpath($element);

        return $this->client->findElement($webDriver);
    }

    /**
     * @throws NoSuchElementException
     */
    public function getAttribute(string $element, string $attribute): ?string
    {
        return $this->findElement($element)->getAttribute($attribute);
    }

    public function assertSelectorExists(string $selector): self
    {
        // @phpstan-ignore-next-line
        $this->assertNotEmpty($this->crawler->filter($selector));

        return $this;
    }

    public function assertSelectorNotExists(string $selector): self
    {
        // @phpstan-ignore-next-line
        $this->assertEmpty($this->crawler->filter($selector));

        return $this;
    }

    public function assertSelectorTextContains(string $selector, string $text): self
    {
        $this->assertStringContainsString($text, $this->getSelectorText($selector));

        return $this;
    }

    public function assertSelectorTextNotContains(string $selector, string $text): self
    {
        $this->assertStringNotContainsString($text, $this->getSelectorText($selector));

        return $this;
    }

    public function assertPageTitleSame(string $expected): self
    {
        $this->assertSame($expected, $this->client->getTitle());

        return $this;
    }

    public function assertPageTitleContains(string $expected): self
    {
        $this->assertStringContainsString($expected, $this->client->getTitle());

        return $this;
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function assertSelectorWillExist(string $selector): self
    {
        $this->client->waitFor($selector);
        $this->assertSelectorExists($selector);

        return $this;
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function assertSelectorWillNotExist(string $selector): self
    {
        $this->client->waitForStaleness($selector);
        $this->assertSelectorNotExists($selector);

        return $this;
    }

    /**
     * @throws NoSuchElementException
     */
    public function assertSelectorIsVisible(string $selector): self
    {
        $this->assertTrue($this->findElement($selector)->isDisplayed());

        return $this;
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function assertSelectorWillBeVisible(string $selector): self
    {
        $this->client->waitForVisibility($selector);
        $this->assertSelectorIsVisible($selector);

        return $this;
    }

    /**
     * @throws NoSuchElementException
     */
    public function assertSelectorIsNotVisible(string $selector): self
    {
        $element = self::findElement($selector);
        $this->assertFalse($element->isDisplayed());

        return $this;
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function assertSelectorWillNotBeVisible(string $selector): self
    {
        $this->client->waitForInvisibility($selector);
        $this->assertSelectorIsNotVisible($selector);

        return $this;
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function assertSelectorWillContain(string $selector, string $text): self
    {
        $this->client->waitForElementToContain($selector, $text);
        $this->assertSelectorTextContains($selector, $text);

        return $this;
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function assertSelectorWillNotContain(string $selector, string $text): self
    {
        $this->client->waitForElementToNotContain($selector, $text);
        $this->assertSelectorTextNotContains($selector, $text);

        return $this;
    }

    /**
     * @throws NoSuchElementException
     */
    public function assertSelectorIsEnabled(string $selector): self
    {
        $this->assertTrue($this->findElement($selector)->isEnabled());

        return $this;
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function assertSelectorWillBeEnabled(string $selector): self
    {
        $this->client->waitForEnabled($selector);
        $this->assertSelectorAttributeContains($selector, 'disabled');

        return $this;
    }

    /**
     * @throws NoSuchElementException
     */
    public function assertSelectorIsDisabled(string $selector): self
    {
        $this->assertFalse($this->findElement($selector)->isEnabled());

        return $this;
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function assertSelectorWillBeDisabled(string $selector): self
    {
        $this->client->waitForDisabled($selector);
        $this->assertSelectorAttributeContains($selector, 'disabled', 'true');

        return $this;
    }

    /**
     * @throws NoSuchElementException
     */
    public function assertSelectorAttributeContains(string $selector, string $attribute, ?string $text = null): self
    {
        if ($text === null) {
            $this->assertNull($this->getAttribute($selector, $attribute));

            return $this;
        }

        $this->assertStringContainsString($text, $this->getAttribute($selector, $attribute));

        return $this;
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function assertSelectorAttributeWillContain(string $selector, string $attribute, string $text): self
    {
        $this->client->waitForAttributeToContain($selector, $attribute, $text);
        $this->assertSelectorAttributeContains($selector, $attribute, $text);

        return $this;
    }

    /**
     * @throws NoSuchElementException
     */
    public function assertSelectorAttributeNotContains(string $selector, string $attribute, string $text): self
    {
        $this->assertStringNotContainsString($text, $this->getAttribute($selector, $attribute));

        return $this;
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function assertSelectorAttributeWillNotContain(string $selector, string $attribute, string $text): self
    {
        $this->client->waitForAttributeToNotContain($selector, $attribute, $text);
        $this->assertSelectorAttributeNotContains($selector, $attribute, $text);

        return $this;
    }
}
