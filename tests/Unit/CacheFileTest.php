<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Unit;

use Core\Cache\Cache;
use Core\Enums\CacheDriver;
use Core\Support\Config;
use PHPUnit\Framework\TestCase;

class CacheFileTest extends TestCase
{
    public function test_can_store(): void
    {
        $driver = config('cache.driver');

        Config::updateEnv(['CACHE_DRIVER' => CacheDriver::FILE]);

        $key = uniqid();
        $cache = new Cache;

        $cache->store($key, ['key' => $key, 'value' => config('app.name')]);

        $data = $cache->get($key);

        $this->assertEquals($data['key'], $key);
        $this->assertEquals($data['value'], config('app.name'));

        $cache->delete($key);

        Config::updateEnv(['CACHE_DRIVER' => $driver]);
    }
}
