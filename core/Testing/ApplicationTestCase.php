<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Testing;

use App\Database\Models\User;
use Core\Database\Model;
use Core\Support\Auth;
use Core\Database\Repository;
use PHPUnit\Framework\TestCase;
use Core\Http\Client\Client;

/**
 * Manage application tests
 */
class ApplicationTestCase extends TestCase
{
    private Client $client;
    private array $headers;
    private string $token;

    protected function setUp(): void
    {
        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[\Core\Testing\Concerns\LoadFaker::class])) {
            $this->loadFaker();
        }

        $this->token = '';
        $this->headers = [];

        parent::setUp();
    }

    protected function tearDown(): void
    {
        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[\Core\Testing\Concerns\RefreshDatabase::class])) {
            $this->refreshDatabase();
        }
    }

    protected function url(string $uri): string
    {
        return config('testing.host') . ':' . config('testing.port') . '/' . ltrim($uri, '/');
    }

    protected function getBody()
    {
        return $this->client->getBody()[0];
    }

    protected function getStatusCode()
    {
        return $this->client->getStatusCode()[0];
    }

    protected function getHeaders(?string $key = null)
    {
        $headers = $this->client->getHeaders()[0];

        return is_null($key) ? $headers : $headers[$key][0];
    }

    protected function getSession()
    {
        return !array_key_exists('session', $this->getHeaders()) ? []
            : json_decode($this->getHeaders('session'), true);
    }

    protected function setHeaders(array $headers): array
    {
        return array_merge($this->headers, $headers);
    }

    protected function getSessionKey(string $name): string
    {
        return strtolower(config('app.name')) . '_' . $name;
    }

    public function auth(Model|User $user): self
    {
        $this->token = Auth::createToken($user->email);
        $this->headers = array_merge($this->headers, ['Authorization' => "Bearer $this->token"]);

        return $this;
    }

    public function createFileUpload(string $filename, ?string $mime_type = null, ?string $name = null) 
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

    public function assertStatusOk(): self
    {
        $this->assertStatusEquals(200);
        return $this;
    }

    public function assertStatusEquals(int $expected): self
    {
        $this->assertEquals($expected, $this->getStatusCode());
        return $this;
    }

    public function assertStatusDoesNotEquals(int $expected): self
    {
        $this->assertNotEquals($expected, $this->getStatusCode());
        return $this;
    }

    public function assertResponseHasJson(array $expected): self
    {
        $this->assertJsonStringEqualsJsonString(json_encode($expected), $this->getBody());
        return $this;
    }

    public function assertResponseDoesNotHaveJson(array $expected): self
    {
        $this->assertJsonStringNotEqualsJsonString(json_encode($expected), $this->getBody());
        return $this;
    }

    public function assertRedirected(int $expected = 302): self
    {
        $this->assertStatusEquals($expected);
        return $this;
    }

    public function assertNotRedirected(int $expected = 302): self
    {
        $this->assertStatusDoesNotEquals($expected);
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
        $this->assertArrayHasKey($this->getSessionKey($expected), $this->getSession());
        return $this;
    }

    public function assertSessionDoesNotExists(string $expected): self
    {
        $this->assertArrayNotHasKey($this->getSessionKey($expected), $this->getSession());
        return $this;
    }

    public function assertSessionHas(string $key, $value): self
    {
        if (!isset($this->getSession()[$this->getSessionKey($key)])) {
            $this->assertFalse(false);
            return $this;
        }

        $this->assertEquals($value, $this->getSession()[$this->getSessionKey($key)]);
        return $this;
    }

    public function assertSessionDoesNotHave(string $key, $value): self
    {
        if (!isset($this->getSession()[$this->getSessionKey($key)])) {
            $this->assertFalse(false);
            return $this;
        }

        $this->assertNotEquals($value, $this->getSession()[$this->getSessionKey($key)]);
        return $this;
    }

    public function assertSessionHasErrors(): self
    {
        $this->assertFalse(empty($this->getSession()[$this->getSessionKey('errors')]));
        return $this;
    }

    public function assertSessionDoesNotHaveErrors(): self
    {
        $this->assertTrue(empty($this->getSession()[$this->getSessionKey('errors')]));
        return $this;
    }

    public function dump()
    {
        dd($this->getBody());
    }

    public function dumpHeaders()
    {
        dd($this->getHeaders());
    }

    public function dumpSession()
    {
        dd($this->getSession());
    }
}
