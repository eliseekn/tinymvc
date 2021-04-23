<?PHP

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Framework\Http\Request;
use Framework\System\Storage;

/**
 * Define application environnement
 */

//application root path
define('APP_ROOT', __DIR__  . DIRECTORY_SEPARATOR);

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
    ini_set('error_log', Storage::path(config('storage.logs'))->file('tinymvc_logs_' . date('m_d_y') . '.log'));
} else {
    ini_set('log_errors', 0);
}

//handle errors exceptions
function handleExceptions($e)
{
    throw new ErrorException($e->getMessage(), $e->getCode(), 1, $e->getFile(), $e->getLine(), $e->getPrevious());
}

//set exception handler
set_exception_handler('handleExceptions');

//remove PHP maximum execution time 
set_time_limit(0);

//load .env file
if (!Storage::path()->isFile('.env') && !empty((new Request())->uri())) {
    throw new Exception('Missing ".env" file. Run "php console app:setup" on your terminal to setup application.');
}

if (Storage::path()->isFile('.env')) {
    load_env();
}
