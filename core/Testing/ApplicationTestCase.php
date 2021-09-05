<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Testing;

use Core\Database\Repository;
use Core\Http\Client;
use PHPUnit\Framework\TestCase;
use Faker\Factory;

/**
 * Manage application tests
 */
class ApplicationTestCase extends TestCase
{
    /**
     * @var \Core\Http\Client
     */
    public $client;

    /**
     * @var \Faker\Generator
     */
    public $faker;

    protected function setUp(): void
    {
        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[\Core\Testing\Traits\RefreshDatabase::class])) {
            $this->refreshDatabase();
        }

        $this->faker = Factory::create(config('app.lang'));
    }

    protected function url(string $uri)
    {
        return config('app.url') . $uri;
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
        if (is_null($key)) {
            return $this->client->getHeaders()[0];
        }

        return $this->client->getHeaders()[0][$key][0];
    }

    protected function getSession()
    {
        if (array_key_exists('session', $this->getHeaders())) {
            return json_decode($this->getHeaders('session'), true);
        }

        return [];
    }

    public function get(string $uri, array $headers = [])
    {
        $this->client = Client::get($this->url($uri), [], [], $headers);
        return $this;
    }

    public function post(string $uri, array $data = [], array $headers = [])
    {
        $this->client = Client::post($this->url($uri), $headers, $data);
        return $this;
    }

    public function patch(string $uri, array $data = [], array $headers = [])
    {
        $this->client = Client::patch($this->url($uri), $headers, $data);
        return $this;
    }

    public function put(string $uri, array $data = [], array $headers = [])
    {
        $this->client = Client::put($this->url($uri), $headers, $data);
        return $this;
    }

    public function delete(string $uri, array $headers = [])
    {
        $this->client = Client::delete($this->url($uri), $headers, [],);
        return $this;
    }

    public function postJson(string $uri, array $data = [], array $headers = [])
    {
        $this->client = Client::post($this->url($uri), $data, $headers, true);
        return $this;
    }

    public function patchJson(string $uri, array $data = [], array $headers = [])
    {
        $this->client = Client::patch($this->url($uri), $data, $headers, true);
        return $this;
    }

    public function putJson(string $uri, array $data = [], array $headers = [])
    {
        $this->client = Client::put($this->url($uri), $headers, $data, true);
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
        $this->assertTrue(array_key_exists(strtolower(config('app.name')) . '_' . $expected, $this->getSession()));
    }

    public function assertSessionDoesNotExists(string $expected)
    {
        $this->assertFalse(array_key_exists(strtolower(config('app.name')) . '_' . $expected, $this->getSession()));
    }

    public function assertSessionHas(string $key, $value)
    {
        $this->assertEquals($value, $this->getSession()[strtolower(config('app.name')) . '_' . $key]);
    }

    public function assertSessionDoesNotHave(string $key, $value)
    {
        $this->assertNotEquals($value, $this->getSession()[strtolower(config('app.name')) . '_' . $key]);
    }

    public function assertSessionHasErrors()
    {
        $this->assertSessionExists('errors');
    }

    public function assertSessionDoesNotHaveErrors()
    {
        $this->assertSessionDoesNotExists('errors');
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
