<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Testing;

use Core\Database\Model;
use Core\Database\Repository;
use Core\Http\Auth;
use Core\Http\Client\Curl as Client;
use CURLFile;
use PHPUnit\Framework\TestCase;

/**
 * Manage application tests.
 */
abstract class FeatureTestCase extends TestCase
{
    private Client $client;

    private array $headers;

    private string $token;

    protected function setUp(): void
    {
        $this->token = '';
        $this->headers = [];

        parent::setUp();
    }

    protected function url(string $uri): string
    {
        return config('tests.host') . ':' . config('tests.port') . '/' . ltrim($uri, '/');
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
        $headers = $this->client->getHeaders()[0];

        return is_null($key) ? $headers : $headers[$key][0];
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
        $this->headers = array_merge($this->headers, ['Authorization' => "Bearer $this->token"]);

        return $this;
    }

    public function createFileUpload(string $filename, ?string $mime_type = null, ?string $name = null): CURLFile
    {
        $this->headers = array_merge($this->headers, ['Content-Type' => 'multipart/form-data']);

        return curl_file_create($filename, $mime_type, $name);
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

    public function assertStatusEquals(int $expected): self
    {
        $this->assertEquals($expected, $this->getStatusCode());

        return $this;
    }

    public function assertStatusOk(): self
    {
        $this->assertStatusEquals(200);

        return $this;
    }

    public function assertStatusForbidden(): self
    {
        $this->assertStatusEquals(403);

        return $this;
    }

    public function assertStatusUnauthenticated(): self
    {
        $this->assertStatusEquals(401);

        return $this;
    }

    public function assertStatusDoesNotEquals(int $expected): self
    {
        $this->assertNotEquals($expected, $this->getStatusCode());

        return $this;
    }

    public function assertJsonEquals(array $expected): self
    {
        $this->assertJsonStringEqualsJsonString(json_encode($expected), $this->getBody());

        return $this;
    }

    public function assertJsonDoesNotEquals(array $expected): self
    {
        $this->assertJsonStringNotEqualsJsonString(json_encode($expected), $this->getBody());

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
        $this->assertEquals($expected, $this->getHeaders('location'));

        return $this;
    }

    public function assertNotRedirectedToUrl(string $expected): self
    {
        $this->assertNotEquals($expected, $this->getHeaders('location'));

        return $this;
    }

    public function assertView(string $view): self
    {
        $this->assertEquals($this->getBody(), view($view));

        return $this;
    }

    public function assertNotView(string $view): self
    {
        $this->assertNotEquals($this->getBody(), view($view));

        return $this;
    }

    public function assertDatabaseHas(string $table, array $expected): self
    {
        $result = (new Repository($table))->findMany($expected, 'and')->exists();
        $this->assertTrue($result);

        return $this;
    }

    public function assertDatabaseDoesNotHave(string $table, array $expected): self
    {
        $result = (new Repository($table))->findMany($expected, 'and')->exists();
        $this->assertFalse($result);

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
            $this->assertFalse(false);
        } else {
            $this->assertEquals($value, $this->getSession($this->sessionKey($key)));
        }

        return $this;
    }

    public function assertSessionDoesNotHave(string $key, $value): self
    {
        if (! array_key_exists($this->sessionKey($key), $this->getSession())) {
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
