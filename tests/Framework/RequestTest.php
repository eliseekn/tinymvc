<?php
declare(strict_types = 1);

use Core\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{    
    /**
     * @var \Core\Http\Request
     */
    private $request;

    function setUp(): void
    {
        $_SERVER['REQUEST_URI'] = '/test?key=value';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REMOTE_ADDR'] = '8.8.8.8';
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer abcde1234';
        $_POST['pKey'] = 'pValue';
        $_POST['pKey1'] = '';
        $_GET['qKey'] = 'qValue';
        $_FILES['file'] = [
            'name' => 'name',
            'tmp_name' => 'tmp_name',
            'size' => 1024,
            'type' => 'type',
            'error' => 1
        ];
        
        $this->request = new Request();
    }

    public function testRequestUri()
    {
        $this->assertEquals('/test', $this->request->uri());
    }

    public function testRequestFullUri()
    {
        $this->assertEquals('/test?key=value', $this->request->fullUri());
    }

    public function testRequestMethod()
    {
        $this->assertEquals('GET', $this->request->method());
        $this->assertTrue($this->request->is('GET'));
    }

    public function testRequestRemoteIp()
    {
        $this->assertEquals('8.8.8.8', $this->request->remoteIP());
    }

    public function testRequestQueries()
    {
        $this->assertEquals('qValue', $this->request->queries('qKey'));
    }

    public function testRequestInputs()
    {
        $this->assertEquals('pValue', $this->request->inputs('pKey'));
    }

    public function testRequestHas()
    {
        $this->assertTrue($this->request->has('pKey'));
        $this->assertTrue($this->request->has('qKey'));
        $this->assertFalse($this->request->has('dummy_item'));
    }

    public function testRequestHasQueries()
    {
        $this->assertTrue($this->request->hasQueries('qKey'));
        $this->assertFalse($this->request->hasQueries('dummy_item'));
    }

    public function testRequestHasInputs()
    {
        $this->assertTrue($this->request->hasInputs('pKey'));
        $this->assertFalse($this->request->hasInputs('dummy_item'));
    }

    public function testRequestFilled()
    {
        $this->assertTrue($this->request->filled('pKey'));
        $this->assertFalse($this->request->filled('pKey1'));
    }

    public function testRequestGet()
    {
        $this->assertEquals('qValue', $this->request->get('qKey'));
        $this->assertEquals('pValue', $this->request->get('pKey'));
        $this->assertEquals('', $this->request->get('pKey1'));
    }

    public function testRequestSet()
    {
        $this->request->set('pKey1', 'hello');
        $this->assertEquals('hello', $this->request->get('pKey1'));
    }

    public function testRequestOnly()
    {
        $this->assertEquals(['pKey' => 'pValue'], $this->request->only('pKey'));
    }

    public function testRequestExcept()
    {
        $items = [
            'pKey' => 'pValue',
            'qKey' => 'qValue'
        ];

        $this->assertEquals($items, $this->request->except('pKey1'));
    }

    public function testRequestAll()
    {
        $items = [
            'pKey' => 'pValue',
            'qKey' => 'qValue',
            'pKey1' => ''
        ];

        $this->assertEquals($items, $this->request->all());
    }

    public function testRequestSingleFile()
    {
        $file = $this->request->files('file');

        $this->assertEquals('name', $file->getOriginalFilename());
        $this->assertEquals('tmp_name', $file->getTempFilename());
        $this->assertEquals('type', $file->getFileType());
        $this->assertEquals(1024, $file->getFileSize());
    }

    public function testRequestMultipleFiles()
    {
        $_FILES['file'] = [
            'name' => ['name1', 'name2'],
            'tmp_name' => ['tmp_name1', 'tmp_name2'],
            'size' => [1024, 2024],
            'type' => ['type1', 'type2'],
            'error' => [1, 1]
        ];

        $files = $this->request->files('file', true);

        $this->assertEquals(2, count($files));

        $this->assertEquals('name1', $files[0]->getOriginalFilename());
        $this->assertEquals('tmp_name1', $files[0]->getTempFilename());
        $this->assertEquals('type1', $files[0]->getFileType());
        $this->assertEquals(1024, $files[0]->getFileSize());

        $this->assertEquals('name2', $files[1]->getOriginalFilename());
        $this->assertEquals('tmp_name2', $files[1]->getTempFilename());
        $this->assertEquals('type2', $files[1]->getFileType());
        $this->assertEquals(2024, $files[1]->getFileSize());
    }

    public function testHeadears()
    {
        $this->assertEquals('Bearer abcde1234', $this->request->headers('HTTP_AUTHORIZATION'));
    }

    public function testHttpAuthHeader()
    {
        $header = $this->request->http_auth();

        $this->assertEquals('Bearer', $header[0]);
        $this->assertEquals('abcde1234', $header[1]);
    }
}
