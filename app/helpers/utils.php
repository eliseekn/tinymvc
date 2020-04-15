<?php
/**
* Application => TinyMVC (PHP framework based on MVC architecture)
* File        => utils.php (miscellaneous utils functions)
* Github      => https://github.com/eliseekn/tinymvc
* Copyright   => 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
* Licence     => MIT (https://opensource.org/licenses/MIT)
*/

//generate slug from string
function generate_slug(string $str, string $separator = '-'): string {
	$slug = preg_replace('/[^a-zA-Z0-9]/', $separator, $str);
	$slug = strtolower(trim($slug, $separator));
	$slug = preg_replace('/\-{2,}/', $separator, $slug);
	return $slug;
}

//generate exerpt from text
function generate_exerpt(string $text, int $size = 290, string $end_string = '[...]'): string {
	return mb_strimwidth($text, 0, $size, $end_string);
}
