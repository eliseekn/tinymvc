<?php
/**
* Application => TinyMVC (PHP framework based on MVC architecture)
* File        => debug.php (miscellaneous debugging utils functions)
* Github      => https://github.com/eliseekn/tinymvc
* Copyright   => 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
* Licence     => MIT (https://opensource.org/licenses/MIT)
*/

//write log messages to file
//type can be 'ERROR', 'WARNING' or 'DEBUG' according to you
//edit LOGS_ROOT in app/core/config.php
function log_message(string $type, string $message): void {
	$log = $type .': '. $message . PHP_EOL;
	$date = date('m_d_Y', time());
    $log_file = LOGS_ROOT .'logs_'. $date .'.txt';
	file_put_contents($log_file, $log, FILE_APPEND | LOCK_EX);
}

//dump variables and exit
function dump_exit(...$data): void {
	foreach ($data as $d) {
		echo '<pre>';
		print_r($d);
		echo '</pre>';
	}

	exit();
}
