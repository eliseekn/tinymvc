<?php
declare(strict_types = 1);

use Framework\Routing\Route;
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

    public function testRouteUri()
    {
        Route::get('uri', function () {})->register();
        $this->assertArrayHasKey('/uri',  Route::$routes);
        $this->resetRoutes();
    }

    public function testGetMethod()
    {
        Route::get('uri', function () {})->register();
        $this->assertEquals('GET|HEAD', $this->getRoute('/uri', 'method'));
        $this->resetRoutes();
    }

    public function testPostMethod()
    {
        Route::post('uri', function () {})->register();
        $this->assertEquals('POST', $this->getRoute('/uri', 'method'));
        $this->resetRoutes();
    }

    public function testPutMethod()
    {
        Route::put('uri', function () {})->register();
        $this->assertEquals('PUT', $this->getRoute('/uri', 'method'));
        $this->resetRoutes();
    }

    public function testPatchMethod()
    {
        Route::patch('uri', function () {})->register();
        $this->assertEquals('PATCH', $this->getRoute('/uri', 'method'));
        $this->resetRoutes();
    }

    public function testOptionsMethod()
    {
        Route::options('uri', function () {})->register();
        $this->assertEquals('OPTIONS', $this->getRoute('/uri', 'method'));
        $this->resetRoutes();
    }

    public function testMatchMethod()
    {
        Route::match('GET|POST', 'uri', function () {})->register();
        $this->assertEquals('GET|POST', $this->getRoute('/uri', 'method'));
        $this->resetRoutes();
    }

    public function testAnyMethod()
    {
        Route::any('uri', function () {})->register();
        $this->assertRegExp('/POST|GET|PUT/', $this->getRoute('/uri', 'method'));
        $this->resetRoutes();
    }

    public function testName()
    {
        Route::get('uri', function () {})->name('name')->register();
        $this->assertEquals('name', $this->getRoute('/uri', 'name'));
        $this->resetRoutes();
    }

    public function testMiddlewares()
    {
        $middlewares = ['middleware1', 'middleware2'];

        Route::get('uri', function () {})->middlewares(...$middlewares)->register();

        $this->assertEquals($middlewares, $this->getRoute('/uri', 'middlewares'));
        $this->resetRoutes();
    }

    public function testLocked()
    {
        $roles = ['role1', 'role2'];

        Route::get('uri', function () {})->locked(...$roles)->register();

        $this->assertEquals($roles, $this->getRoute('/uri', 'roles'));
        $this->resetRoutes();
    }

    public function testGroupMiddlewares()
    {
        $middlewares = ['middleware1', 'middleware2'];

        Route::groupMiddlewares($middlewares, function () {
            Route::get('uri', function () {});
        })->register();

        $this->assertEquals($middlewares, $this->getRoute('/uri', 'middlewares'));
        $this->resetRoutes();
    }

    public function testGroupPrefix()
    {
        Route::groupPrefix('prefix', function () {
            Route::get('uri', function () {});
        })->register();

        $this->assertArrayHasKey('/prefix/uri',  Route::$routes);
        $this->resetRoutes();
    }

    public function testGroup()
    {
        $middlewares = ['middleware1', 'middleware2'];

        Route::group(['prefix' => 'prefix', 'middlewares' => $middlewares], function () {
            Route::get('uri', function () {});
        })->register();

        $this->assertEquals($middlewares, $this->getRoute('/prefix/uri', 'middlewares'));
        $this->assertArrayHasKey('/prefix/uri',  Route::$routes);
        $this->resetRoutes();
    }
}
