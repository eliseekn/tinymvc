<?php

/**
* TinyMVC
*
* MIT License
*
* Copyright (c) 2019, N'Guessan Kouadio Elisée
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in all
* copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*
* @author: N'Guessan Kouadio Elisée AKA eliseekn
* @contact: eliseekn@gmail.com - https://eliseekn.netlify.app
* @version: 1.0.0.0
*/

//miscellaneous utils functions

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

//write log messages to file
//type can be 'ERROR', 'WARNING' or 'DEBUG' according to you
//edit LOGS_ROOT in app/core/config.php
function save_log(string $type, string $message) {
	$log = $type .': '. $message . PHP_EOL;
	$date = date('m_d_Y', time());
	$log_file = LOGS_ROOT .'logs_'. $date .'.txt';
	file_put_contents($log_file, $log, FILE_APPEND | LOCK_EX);
}

//dump variables and exit
function dump_exit($data) {
	echo '<pre>';
	print_r($data);
	echo '</pre>';
	exit();
}

//redirect url
function redirect(string $url) {
	header('Location: '. WEB_ROOT . $url);
	exit();
}