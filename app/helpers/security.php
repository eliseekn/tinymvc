<?php
/**
* Application => TinyMVC (PHP framework based on MVC architecture)
* File        => security.php (security utils functions)
* Github      => https://github.com/eliseekn/tinymvc
* Copyright   => 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
* Licence     => MIT (https://opensource.org/licenses/MIT)
*/

//sanitize html and other scripting language
function sanitize_input($input): string {
    $sanitized_input = stripslashes($input);
    $sanitized_input = strip_tags($sanitized_input);
    $sanitized_input = htmlentities($sanitized_input);

    return $sanitized_input;
}

//encoding url
function encode_url($url): string {
    return urlencode($url);
}

//hash string with password_hash() PHP function
function hash_string(string $str): string {
    return password_hash($str, PASSWORD_DEFAULT);
}

//check hashed string
function check_hash(string $str, string $hash): bool {
    return password_verify($str, $hash);
}
