<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Testing;

use Core\Database\Model;
use Core\Enums\HttpAuthMethod;
use Core\Enums\HttpCode;
use Core\Http\Auth;
use Core\Http\Client\Client;
use Core\Testing\Traits\DatabaseTestCaseTrait;
use CURLFile;
use PHPUnit\Framework\TestCase;

/**
 * Manage application tests.
 */
abstract class FeatureTestCase extends TestCase
{
    use DatabaseTestCaseTrait;

    private Client $client;

    private array $headers;

    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->token = '';
        $this->headers = [];
    }

    protected function url(string $uri): string
    {
        return config('tests.url.host') . ':' . config('tests.url.port') . '/' . ltrim($uri, '/');
    }

    protected function getBody(): string
    {
        return $this->client->getBody()[0];
    }

    protected function getStatusCode(): int
    {
        return $this->client->getStatusCode()[0];
    }

    protected function getHeaders(?string $key = null): mixed
    {
        $headers = $this->client->getHeaders();

        if (empty($headers)) {
            return [];
        }

        return is_null($key) ? $headers[0] : $headers[0][$key][0];
    }

    protected function getSession(?string $key = null): mixed
    {
        if (! array_key_exists('session', $this->getHeaders())) {
            return [];
        }

        $data = json_decode($this->getHeaders('session'), true);

        return is_null($key) ? $data : $data[$key];
    }

    protected function setHeaders(array $headers): array
    {
        return array_merge($this->headers, $headers);
    }

    protected function sessionKey(string $name): string
    {
        return strtolower(config('app.name')) . '_' . $name;
    }

    public function auth(Model $user): self
    {
        $this->token = Auth::createToken($user->get('email'));
        $this->headers = array_merge($this->headers, ['Authorization' => HttpAuthMethod::BEARER . ' ' . $this->token]);

        return $this;
    }

    public function file(string $filename, ?string $mime_type = null, ?string $name = null): CURLFile
    {
        return new CURLFile($filename, $mime_type, $name);
    }

    public function get(string $uri, array $headers = []): self
    {
        $this->client = Client::get($this->url($uri), $this->setHeaders($headers));

        return $this;
    }

    public function post(string $uri, array $data = [], array $headers = []): self
    {
        $this->client = Client::post($this->url($uri), $data, $this->setHeaders($headers));

        return $this;
    }

    public function patch(string $uri, array $data = [], array $headers = []): self
    {
        $this->client = Client::patch($this->url($uri), $data, $this->setHeaders($headers));

        return $this;
    }

    public function put(string $uri, array $data = [], array $headers = []): self
    {
        $this->client = Client::put($this->url($uri), $data, $this->setHeaders($headers));

        return $this;
    }

    public function delete(string $uri, array $headers = []): self
    {
        $this->client = Client::delete($this->url($uri), $this->setHeaders($headers));

        return $this;
    }

    public function postJson(string $uri, array $data = [], array $headers = []): self
    {
        $this->client = Client::post($this->url($uri), $data, $this->setHeaders($headers), true);

        return $this;
    }

    public function getJson(string $uri, array $headers = []): self
    {
        $this->client = Client::get($this->url($uri), $this->setHeaders($headers), true);

        return $this;
    }

    public function patchJson(string $uri, array $data = [], array $headers = []): self
    {
        $this->client = Client::patch($this->url($uri), $data, $this->setHeaders($headers), true);

        return $this;
    }

    public function putJson(string $uri, array $data = [], array $headers = []): self
    {
        $this->client = Client::put($this->url($uri), $this->setHeaders($headers), $data, true);

        return $this;
    }

    public function deleteJson(string $uri, array $headers = []): self
    {
        $this->client = Client::delete($this->url($uri), $this->setHeaders($headers), true);

        return $this;
    }

    public function assertStatusEquals(int $expected): self
    {
        $this->assertEquals($expected, $this->getStatusCode());

        return $this;
    }

    public function assertStatusOk(): self
    {
        $this->assertStatusEquals(HttpCode::OK);

        return $this;
    }

    public function assertStatusForbidden(): self
    {
        $this->assertStatusEquals(HttpCode::FORBIDDEN);

        return $this;
    }

    public function assertStatusUnauthenticated(): self
    {
        $this->assertStatusEquals(HttpCode::UNAUTHORIZED);

        return $this;
    }

    public function assertStatusNotFound(): self
    {
        $this->assertStatusEquals(HttpCode::NOT_FOUND);

        return $this;
    }

    public function assertStatusFound(): self
    {
        $this->assertStatusEquals(HttpCode::FOUND);

        return $this;
    }

    public function assertStatusBadRequest(): self
    {
        $this->assertStatusEquals(HttpCode::BAD_REQUEST);

        return $this;
    }

    public function assertStatusDoesNotEquals(int $expected): self
    {
        $this->assertNotEquals($expected, $this->getStatusCode());

        return $this;
    }

    public function assertJsonContains(array $expected): self
    {
        foreach ($expected as $key => $value) {
            $this->assertArrayHasKey($key, $expected);
            $this->assertEquals($value, $value);
        }

        return $this;
    }

    public function assertRedirectedToUrl(string $expected): self
    {
        $this->assertEquals(url($expected), $this->getHeaders('location'));

        return $this;
    }

    public function assertNotRedirectedToUrl(string $expected): self
    {
        $this->assertNotEquals(url($expected), $this->getHeaders('location'));

        return $this;
    }

    public function assertSessionExists(string $expected): self
    {
        $this->assertArrayHasKey($this->sessionKey($expected), $this->getSession());

        return $this;
    }

    public function assertSessionDoesNotExists(string $expected): self
    {
        $this->assertFalse(array_key_exists($this->sessionKey($expected), $this->getSession()));

        return $this;
    }

    public function assertSessionHas(string $key, $value): self
    {
        if (! array_key_exists($this->sessionKey($key), $this->getSession())) {
            // @phpstan-ignore-next-line
            $this->assertFalse(false);
        } else {
            $this->assertEquals($value, $this->getSession($this->sessionKey($key)));
        }

        return $this;
    }

    public function assertSessionDoesNotHave(string $key, $value): self
    {
        if (! array_key_exists($this->sessionKey($key), $this->getSession())) {
            // @phpstan-ignore-next-line
            $this->assertFalse(false);
        } else {
            $this->assertNotEquals($value, $this->getSession($this->sessionKey($key)));
        }

        return $this;
    }

    public function assertSessionHasErrors(): self
    {
        $this->assertFalse(empty($this->getSession()[$this->sessionKey('errors')]));

        return $this;
    }

    public function assertSessionDoesNotHaveErrors(): self
    {
        $this->assertTrue(empty($this->getSession()[$this->sessionKey('errors')]));

        return $this;
    }

    public function dump(): void
    {
        error_log($this->getBody());
        dd($this->getBody());
    }

    public function dumpHeaders(): void
    {
        dd($this->getHeaders());
    }

    public function dumpSession(): void
    {
        dd($this->getSession());
    }
}
