<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Testing;

use Core\Support\Auth;
use Core\Database\Repository;
use PHPUnit\Framework\TestCase;
use Core\Http\Client\Curl as Client;

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
    }

    protected function tearDown(): void
    {
        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[\Core\Testing\Concerns\RefreshDatabase::class])) {
            $this->refreshDatabase();
        }
    }

    protected function url(string $uri)
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

    protected function setHeaders(array $headers)
    {
        return array_merge($this->headers, $headers);
    }

    protected function getSessionKey(string $name)
    {
        return strtolower(config('app.name')) . '_' . $name;
    }

    /**
     * @param \Core\Database\Model|\App\Database\Models\User $user
     */
    public function actingAs($user)
    {
        $this->token = Auth::createToken($user->email);
        $this->headers = ['Authorization' => "Bearer {$this->token}"];

        return $this;
    }

    public function createFileUpload(string $filename, ?string $mime_type = null, ?string $name = null) 
    {
        $this->headers = ['Content-Type' => 'multipart/form-data'];
        return curl_file_create($filename, $mime_type, $name);
    }

    public function get(string $uri, array $headers = [])
    {
        $this->client = Client::get($this->url($uri), $this->setHeaders($headers));
        return $this;
    }

    public function post(string $uri, array $data = [], array $headers = [])
    {
        $this->client = Client::post($this->url($uri), $data, $this->setHeaders($headers));
        return $this;
    }

    public function patch(string $uri, array $data = [], array $headers = [])
    {
        $this->client = Client::patch($this->url($uri), $data, $this->setHeaders($headers));
        return $this;
    }

    public function put(string $uri, array $data = [], array $headers = [])
    {
        $this->client = Client::put($this->url($uri), $data, $this->setHeaders($headers));
        return $this;
    }

    public function delete(string $uri, array $headers = [])
    {
        $this->client = Client::delete($this->url($uri), $this->setHeaders($headers));
        return $this;
    }

    public function postJson(string $uri, array $data = [], array $headers = [])
    {
        $this->client = Client::post($this->url($uri), $data, $this->setHeaders($headers), true);
        return $this;
    }

    public function patchJson(string $uri, array $data = [], array $headers = [])
    {
        $this->client = Client::patch($this->url($uri), $data, $this->setHeaders($headers), true);
        return $this;
    }

    public function putJson(string $uri, array $data = [], array $headers = [])
    {
        $this->client = Client::put($this->url($uri), $this->setHeaders($headers), $data, true);
        return $this;
    }

    public function assertStatusOk()
    {
        $this->assertStatusEquals(200);
    }

    public function assertStatusEquals(int $expected)
    {
        $this->assertEquals($expected, $this->getStatusCode());
    }

    public function assertStatusDoesNotEquals(int $expected)
    {
        $this->assertNotEquals($expected, $this->getStatusCode());
    }

    public function assertResponseHasJson(array $expected)
    {
        $this->assertJsonStringEqualsJsonString(json_encode($expected), $this->getBody());
    }

    public function assertResponseDoesNotHaveJson(array $expected)
    {
        $this->assertJsonStringNotEqualsJsonString(json_encode($expected), $this->getBody());
    }

    public function assertRedirected(int $expected = 302)
    {
        $this->assertStatusEquals($expected);
    }

    public function assertNotRedirected(int $expected = 302)
    {
        $this->assertStatusDoesNotEquals($expected);
    }

    public function assertRedirectedToUrl(string $expected)
    {
        $this->assertEquals($expected, $this->getHeaders('location'));
    }

    public function assertNotRedirectedToUrl(string $expected)
    {
        $this->assertNotEquals($expected, $this->getHeaders('location'));
    }

    public function assertDatabaseHas(string $table, array $expected)
    {
        $result = (new Repository($table))->findMany($expected, 'and')->exists();
        $this->assertTrue($result);
    }

    public function assertDatabaseDoesNotHave(string $table, array $expected)
    {
        $result = (new Repository($table))->findMany($expected, 'and')->exists();
        $this->assertFalse($result);
    }

    public function assertSessionExists(string $expected)
    {
        $this->assertTrue(array_key_exists($this->getSessionKey($expected), $this->getSession()));
    }

    public function assertSessionDoesNotExists(string $expected)
    {
        $this->assertFalse(array_key_exists($this->getSessionKey($expected), $this->getSession()));
    }

    public function assertSessionHas(string $key, $value)
    {
        if (!isset($this->getSession()[$this->getSessionKey($key)])) {
            $this->assertFalse(false);
            return;
        }

        $this->assertEquals($value, $this->getSession()[$this->getSessionKey($key)]);
    }

    public function assertSessionDoesNotHave(string $key, $value)
    {
        if (!isset($this->getSession()[$this->getSessionKey($key)])) {
            $this->assertFalse(false);
            return;
        }

        $this->assertNotEquals($value, $this->getSession()[$this->getSessionKey($key)]);
    }

    public function assertSessionHasErrors()
    {
        $this->assertFalse(empty($this->getSession()[$this->getSessionKey('errors')]));
    }

    public function assertSessionDoesNotHaveErrors()
    {
        $this->assertTrue(empty($this->getSession()[$this->getSessionKey('errors')]));
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
