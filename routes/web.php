<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

use App\Tasks\SendEmail;
use Core\Http\Routing\Route;
use Core\Task\Task;

/*
 * Web routes
 */

Route::view('/', 'index')->register();

Route::get('/task/queue', function () {
    Task::schedule(new SendEmail)->at(carbon()->addMinutes(2)->timestamp);
    response()->data('Hello world !')->send();
})->register();
