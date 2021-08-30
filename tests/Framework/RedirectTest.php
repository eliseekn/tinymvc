<?php

use Core\Routing\Route;
use Core\Support\Cookies;
use Core\Support\Session;
use PHPUnit\Framework\TestCase;

class RedirectTest extends TestCase
{
    private function resetRoutes()
    {
        Route::$routes = [];
    }

    private function clearSession(string ...$name)
    {
        Session::flush(...$name);
    }

    private function clearCookies(string $name)
    {
        Cookies::delete($name);
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

    public function testRedirectBack()
    {
        Session::create('history', [
            url('test/1'),
            url('test/2'),
            url('test/3')
        ]);

        $this->assertEquals(url('test/2'), redirect()->back()->url);
        $this->resetRoutes();
        $this->clearSession('history');
    }

    public function testRedirectWith()
    {
        redirect()->with('key', 'value');

        $this->assertEquals('value', Session::get('key'));
        $this->clearSession('key');
    }

    public function testRedirectWithInputs()
    {
        redirect()->withInputs(['key' => 'value']);

        $this->assertTrue(array_key_exists('key', Session::get('inputs')));
        $this->clearSession('inputs');
    }

    public function testRedirectWithErrors()
    {
        redirect()->withErrors(['key' => 'value']);

        $this->assertTrue(array_key_exists('key', Session::get('errors')));
        $this->clearSession('errors');
    }

    //BUG: Cannot modify header information - headers already sent by...
    /* public function testRedirectWithCookie()
    {
        redirect()->withCookie('key', 'value');

        $this->assertEquals('value', Cookies::get('key'));
        $this->clearCookies('key');
    } */
}
