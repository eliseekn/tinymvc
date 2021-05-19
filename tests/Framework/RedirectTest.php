<?php
declare(strict_types = 1);

use Core\Routing\Route;
use Core\System\Session;
use PHPUnit\Framework\TestCase;

class RedirectTest extends TestCase
{
    private function resetRoutes()
    {
        Route::$routes = [];
    }

    public function testRedirectUrl()
    {
        $this->assertEquals(url('test'), redirect()->url('test')->url);
    }

    public function testRedirectRoute()
    {
        Route::get('test', function () {})->name('test.name')->register();
        $this->assertEquals(url('test'), redirect()->route('test.name')->url);
        $this->resetRoutes();
    }

    /* public function testRedirectBack()
    {
        Session::create('history', [
            url('test/1'),
            url('test/2'),
            url('test/3')
        ]);

        $this->assertEquals(url('test/2'), redirect()->back()->url);
        $this->resetRoutes();
    } */
}
