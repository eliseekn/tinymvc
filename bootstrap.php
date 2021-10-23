<?PHP

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Core\Routing\Route;
use Core\Support\Config;
use Core\Support\Whoops;
use Core\Support\Storage;

/**
 * Setup application
 */

define('DS', DIRECTORY_SEPARATOR);
define('APP_ROOT', __DIR__  . DS);

set_time_limit(0);

Whoops::register();

$storage = Storage::path(absolute_path('storage'));

if (!$storage->isDir()) $storage->createDir();
if (!$storage->path(config('storage.logs'))->isDir()) $storage->createDir();
if (!$storage->path(config('storage.cache'))->isDir()) $storage->createDir();
if (!$storage->path(config('storage.sqlite'))->isDir()) $storage->createDir();

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

function handleExceptions($e)
{
    throw new ErrorException($e->getMessage(), $e->getCode(), 1, $e->getFile(), $e->getLine(), $e->getPrevious());
}

set_exception_handler('handleExceptions');

if (!Storage::path()->isFile('.env')) {
    throw new Exception('Copy ".env.example" file to ".env" then edit it or run "php console app:setup" console command to setup application');
}

Config::loadEnv();
Route::loadFiles();
