<?PHP

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Core\Http\Request;
use Core\Support\Whoops;
use Core\Support\Config;
use Core\Support\Storage;

/**
 * Define application environnement
 */

//application root path
define('DS', DIRECTORY_SEPARATOR);
define('APP_ROOT', __DIR__  . DS);

//register whoops error handler
Whoops::register();

//errors display
if (config('errors.display') === true) {
    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL);
} else {
    ini_set('display_errors', 0);
}

//errors logging
if (config('errors.log') === true) {
    ini_set('log_errors', 1);
    ini_set('error_log', Storage::path(config('storage.logs'))->file('tinymvc_' . date('m_d_y') . '.log'));
} else {
    ini_set('log_errors', 0);
}

//handle exceptions
function handleExceptions($e)
{
    throw new ErrorException($e->getMessage(), $e->getCode(), 1, $e->getFile(), $e->getLine(), $e->getPrevious());
}

//set exceptions and errors handlers
set_exception_handler('handleExceptions');

//remove PHP maximum execution time 
set_time_limit(0);

//load .env file
if (!Storage::path()->isFile('.env') && !empty((new Request())->uri())) {
    throw new Exception('Run "php console app:setup" console command to setup application or create ".env" file from ".env.example"');
}

Config::loadEnv();