<?PHP

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Core\Events\Event;
use Core\Routing\Route;
use Core\Support\Config;
use Core\Support\Whoops;
use Core\Support\Storage;

/**
 * Application initialization
 */

const APP_ROOT = __DIR__ . DIRECTORY_SEPARATOR;

set_time_limit(0);

Whoops::register();
Storage::init();
Config::loadEnv();
Route::load();
Event::loadListeners();

if (config('errors.display')) {
    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL);
} else {
    ini_set('display_errors', 0);
}

if (config('errors.log')) {
    ini_set('log_errors', 1);
    ini_set('error_log', Storage::path(config('storage.logs'))->file('tinymvc_' . date('m_d_y') . '.log'));
} else {
    ini_set('log_errors', 0);
}

set_exception_handler(function ($e) {
    throw new ErrorException($e->getMessage(), $e->getCode(), 1, $e->getFile(), $e->getLine(), $e->getPrevious());
});
