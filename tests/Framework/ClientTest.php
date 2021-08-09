<?php

use Core\Http\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    /* private $url = 'http://127.0.0.1:8080/';

    private function url(string $uri)
    {
        return $this->url . $uri;
    }

    public function testMultiRequest()
    {
        $client = Client::get([$this->url('uri1'), $this->url('uri2')]);
        $this->assertEquals(2, count($client->getHeaders()));
        $this->assertEquals(2, count($client->getBody()));
        $this->assertEquals(2, count($client->getStatusCode()));
    }

    public function testGetStatusCode200()
    {
        $client = Client::get($this->url('test'));
        $this->assertEquals(200, $client->getStatusCode()[0]);
    }

    public function testPostStatusCode200()
    {
        $client = Client::post($this->url('test'));
        $this->assertEquals(200, $client->getStatusCode()[0]);
    }

    public function testGetStatusCode404()
    {
        $client = Client::get($this->url('dummy-uri'));
        $this->assertEquals(404, $client->getStatusCode()[0]);
    }

    public function testPostStatusCode404()
    {
        $client = Client::post($this->url('dummy-uri'));
        $this->assertEquals(404, $client->getStatusCode()[0]);
    }

    public function testPatchStatusCode200()
    {
        $client = Client::patch($this->url('test'));
        $this->assertEquals(200, $client->getStatusCode()[0]);
    }

    public function testPutStatusCode200()
    {
        $client = Client::put($this->url('test'));
        $this->assertEquals(200, $client->getStatusCode()[0]);
    }

    public function testPatchStatusCode404()
    {
        $client = Client::patch($this->url('dummy-uri'));
        $this->assertEquals(404, $client->getStatusCode()[0]);
    }

    public function testPutStatusCode404()
    {
        $client = Client::put($this->url('dummy-uri'));
        $this->assertEquals(404, $client->getStatusCode()[0]);
    }

    public function testDeleteStatusCode200()
    {
        $client = Client::delete($this->url('test'));
        $this->assertEquals(200, $client->getStatusCode()[0]);
    }

    public function testDeleteStatusCode404()
    {
        $client = Client::delete($this->url('dummy-uri'));
        $this->assertEquals(404, $client->getStatusCode()[0]);
    }

    public function testStatusCode405()
    {
        $client = Client::options($this->url('dummy-uri'));
        $this->assertEquals(405, $client->getStatusCode()[0]);
    }

    public function testGetBodyResponse()
    {
        $client = Client::get($this->url('test'));
        $this->assertEquals('hello', $client->getBody()[0]);
    }

    public function testPostBodyResponse()
    {
        $client = Client::post($this->url('test'));
        $this->assertEquals('hello', $client->getBody()[0]);
    }

    public function testPatchBodyResponse()
    {
        $client = Client::patch($this->url('test'));
        $this->assertEquals('hello', $client->getBody()[0]);
    }

    public function testPutBodyResponse()
    {
        $client = Client::put($this->url('test'));
        $this->assertEquals('hello', $client->getBody()[0]);
    }

    public function testDeleteBodyResponse()
    {
        $client = Client::delete($this->url('test'));
        $this->assertEquals('hello', $client->getBody()[0]);
    }

    public function testGetBodyJsonResponse()
    {
        $client = Client::get($this->url('test/json'));
        $this->assertJsonStringEqualsJsonString(json_encode('hello'), $client->getBody()[0]);
    }

    public function testPostBodyJsonResponse()
    {
        $client = Client::post($this->url('test/json'));
        $this->assertJsonStringEqualsJsonString(json_encode('hello'), $client->getBody()[0]);
    }

    public function testPatchBodyJsonResponse()
    {
        $client = Client::patch($this->url('test/json'));
        $this->assertJsonStringEqualsJsonString(json_encode('hello'), $client->getBody()[0]);
    }

    public function testPutBodyJsonResponse()
    {
        $client = Client::put($this->url('test/json'));
        $this->assertJsonStringEqualsJsonString(json_encode('hello'), $client->getBody()[0]);
    }

    public function testDeleteBodyJsonResponse()
    {
        $client = Client::delete($this->url('test/json'));
        $this->assertJsonStringEqualsJsonString(json_encode('hello'), $client->getBody()[0]);
    }

    public function testSendDataGetRequest()
    {
        $client = Client::get($this->url('test/data?key=value'));
        $this->assertJsonStringEqualsJsonString(json_encode(['key' => 'value']), $client->getBody()[0]);
    }

    public function testSendDataPostRequest()
    {
        $data = ['key' => 'value'];
        $client = Client::post($this->url('test/data'), [], $data);
        $this->assertJsonStringEqualsJsonString(json_encode($data), $client->getBody()[0]);
    } */
}
