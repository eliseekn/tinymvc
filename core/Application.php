<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core;

use Core\Event\Event;
use Core\Exceptions\CoreException;
use Core\Exceptions\Interfaces\HasCustomHttpResponse;
use Core\Exceptions\Interfaces\Reportable;
use Core\Http\Routing\Router;
use Core\Observer\Observer;
use Core\Support\Whoops;
use Core\Task\Task;
use Throwable;

/**
 * Main application.
 */
class Application
{
    /**
     * @throws CoreException
     */
    public function run(): void
    {
        Whoops::register();
        Event::load();
        Task::load();
        Observer::load();

        try {
            Router::dispatch();
        } catch (Throwable $e) {
            if ($e instanceof Reportable) {
                $e->report();
            }

            if ($e instanceof HasCustomHttpResponse) {
                $e->httpResponse()->send();
            } else {
                throw $e;
            }
        }
    }
}
