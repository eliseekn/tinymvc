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

//write log messages to file
class Logger {

    //type can be 'ERROR', 'WARNING' or 'INFORMATION'
    //edit LOGS_ROOT in app/core/config.php
	public static function log(string $type, string $message) {
        $log = $type .': '. $message . PHP_EOL;
        $date = date('m_d_Y', time());
        $log_file = LOGS_ROOT .'logs_'. $date .'.txt';
        file_put_contents($log_file, $log, FILE_APPEND | LOCK_EX);
	}
}
