<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

/**
 * Set application environnement
 */

//remove PHP maximum execution time 
set_time_limit(0);

//set errors display
if (DISPLAY_ERRORS == true) {
    ini_set('display_errors', 1);
    ini_set('error_reporting', -1);
} else {
    ini_set('display_errors', 0);
    ini_set('error_reporting', 0);
}
