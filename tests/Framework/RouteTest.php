<?php

use Core\Routing\Route;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    private function resetRoutes()
    {
        Route::$routes = [];
    }

    private function getRoute(string $uri, string $key)
    {
        return Route::$routes[$uri][$key];
    }

    private function getMethod(string $route)
    {
        return explode(' ', $route, 2)[0];
    }

    public function testRouteUri()
    {
        Route::get('uri', function () {})->register();
        $this->assertArrayHasKey('GET /uri',  Route::$routes);
        $this->resetRoutes();
    }

    public function testGetMethod()
    {
        Route::get('uri', function () {})->register();
        $this->assertEquals('GET', $this->getMethod('GET /uri'));
        $this->resetRoutes();
    }

    public function testPostMethod()
    {
        Route::post('uri', function () {})->register();
        $this->assertEquals('POST', $this->getMethod('POST /uri'));
        $this->resetRoutes();
    }

    public function testPutMethod()
    {
        Route::put('uri', function () {})->register();
        $this->assertEquals('PUT', $this->getMethod('PUT /uri'));
        $this->resetRoutes();
    }

    public function testPatchMethod()
    {
        Route::patch('uri', function () {})->register();
        $this->assertEquals('PATCH', $this->getMethod('PATCH /uri'));
        $this->resetRoutes();
    }

    public function testOptionsMethod()
    {
        Route::options('uri', function () {})->register();
        $this->assertEquals('OPTIONS', $this->getMethod('OPTIONS /uri'));
        $this->resetRoutes();
    }

    public function testMatchMethod()
    {
        Route::match('GET|POST', 'uri', function () {})->register();
        $this->assertEquals('GET|POST', $this->getMethod('GET|POST /uri'));
        $this->resetRoutes();
    }

    public function testAnyMethod()
    {
        Route::any('uri', function () {})->register();
        $this->assertEquals('*', $this->getMethod('* /uri'));
        $this->resetRoutes();
    }

    public function testName()
    {
        Route::get('uri', function () {})->name('name')->register();
        $this->assertEquals('name', $this->getRoute('GET /uri', 'name'));
        $this->resetRoutes();
    }

    public function testGetByName()
    {
        Route::get('test', function () {})->name('test.name')->register();
        $this->assertEquals(url('test'), route('test.name'));
        $this->resetRoutes();
    }

    public function testMiddlewares()
    {
        $middlewares = ['middleware1', 'middleware2'];

        Route::get('uri', function () {})->middlewares(...$middlewares)->register();

        $this->assertEquals($middlewares, $this->getRoute('GET /uri', 'middlewares'));
        $this->resetRoutes();
    }

    public function testLocked()
    {
        $roles = ['role1', 'role2'];

        Route::get('uri', function () {})->lock(...$roles)->register();

        $this->assertEquals($roles, $this->getRoute('GET /uri', 'locked'));
        $this->resetRoutes();
    }

    public function testGroupMiddlewares()
    {
        $middlewares = ['middleware1', 'middleware2'];

        Route::groupMiddlewares($middlewares, function () {
            Route::get('uri', function () {});
        })->register();

        $this->assertEquals($middlewares, $this->getRoute('GET /uri', 'middlewares'));
        $this->resetRoutes();
    }

    public function testGroupPrefix()
    {
        Route::groupPrefix('prefix', function () {
            Route::get('uri', function () {});
        })->register();

        $this->assertArrayHasKey('GET /prefix/uri',  Route::$routes);
        $this->resetRoutes();
    }

    public function testGroup()
    {
        $middlewares = ['middleware1', 'middleware2'];

        Route::group(['prefix' => 'prefix', 'middlewares' => $middlewares], function () {
            Route::get('uri', function () {});
        })->register();

        $this->assertEquals($middlewares, $this->getRoute('GET /prefix/uri', 'middlewares'));
        $this->assertArrayHasKey('GET /prefix/uri',  Route::$routes);
        $this->resetRoutes();
    }

    public function testUriParameterInt()
    {
        Route::get('uri/{param:int}', function (int $param) {})->register();
        $this->assertRegExp('#^uri/(\d+)$#', 'uri/1');
        $this->resetRoutes();
    }

    public function testUriParameterStr()
    {
        Route::get('uri/{param:str}', function (string $param) {})->register();
        $this->assertRegExp('#^uri/([a-zA-Z-_]+)$#', 'uri/str');
        $this->resetRoutes();
    }

    public function testUriParameterAny()
    {
        Route::get('uri/{param:any}', function ($param) {})->register();
        $this->assertRegExp('#^uri/([^/]+)$#', 'uri/1str');
        $this->resetRoutes();
    }

    public function testUriParameterNoType()
    {
        Route::get('uri/{param}', function ($param) {})->register();
        $this->assertRegExp('#^uri/([^/]+)$#', 'uri/1str');
        $this->resetRoutes();
    }

    public function testUriParameterIntOptional()
    {
        Route::get('uri/?{param:int}?', function (?int $param) {})->register();
        $this->assertRegExp('#^uri/?(\d+)?$#', 'uri');
        $this->assertRegExp('#^uri/?(\d+)?$#', 'uri/1');
        $this->resetRoutes();
    }

    public function testUriParameterStrOptional()
    {
        Route::get('uri/?{param:str}?', function (?string $param) {})->register();
        $this->assertRegExp('#^uri/?([a-zA-Z-_]+)?$#', 'uri');
        $this->assertRegExp('#^uri/?([a-zA-Z-_]+)?$#', 'uri/str');
        $this->resetRoutes();
    }

    public function testUriParameterAnyOptional()
    {
        Route::get('uri/?{param:any}?', function ($param = null) {})->register();
        $this->assertRegExp('#^uri/?([^/]+)?$#', 'uri');
        $this->assertRegExp('#^uri/?([^/]+)?$#', 'uri/1str');
        $this->resetRoutes();
    }
    
    public function testUriParameterNoTypeOptional()
    {
        Route::get('uri/?{param}?', function ($param = null) {})->register();
        $this->assertRegExp('#^uri/?([^/]+)?$#', 'uri');
        $this->assertRegExp('#^uri/?([^/]+)?$#', 'uri/1str');
        $this->resetRoutes();
    }
}
