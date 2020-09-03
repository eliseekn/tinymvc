<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

/**
 * Security configuration
 */

//encryption key
define('ENC_KEY', 'BIu5sSkxjVzqiMlHFcX42WpEK3ahUyLG9DQNogZJnmYwArT10R');

//define authentication attempts count, set to 0 to disable 
define('AUTH_ATTEMPTS', 5);

//define authentication attempts lock timeout, in minutes
define('AUTH_ATTEMPTS_TIMEOUT', 1);