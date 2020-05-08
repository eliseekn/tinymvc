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
 * Miscellaneous security utils functions
 */

/**
 * sanitize html and other scripting language
 *
 * @param  string $input input string
 * @return string returns safe string
 */
function sanitize_input(string $input): string
{
    $sanitized_input = stripslashes($input);
    $sanitized_input = strip_tags($sanitized_input);
    $sanitized_input = htmlentities($sanitized_input);
    return $sanitized_input;
}

/**
 * hash string with password_hash() PHP function
 *
 * @param  string $str string to be hashed
 * @return string returns hashed string
 */
function hash_string(string $str): string
{
    return password_hash($str, PASSWORD_DEFAULT);
}

/**
 * compare hashed string with password_verify() PHP function
 *
 * @param  string $str string to be compared
 * @param  string $hash hashed string 
 * @return bool returns true or false
 */
function compare_hash(string $str, string $hash): bool
{
    return password_verify($str, $hash);
}
