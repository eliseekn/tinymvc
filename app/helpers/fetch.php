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

//send http request through curl function

//https://niraeth.com/php-quick-function-for-asynchronous-multi-curl/
//https://stackoverflow.com/questions/9183178/can-php-curl-retrieve-response-headers-and-body-in-a-single-request
//https://www.codexworld.com/post-receive-json-data-using-php-curl/
function fetch(string $method = 'post', array $urls, array $data = [], bool $is_json = false): array {
    $results = array();
    $headers = array();
    $curl_array = array();
    $curl_multi = curl_multi_init(); //init multiple curl processing

    foreach ($urls as $key => $url) {
        $curl_array[$key] = curl_init();
        $curl = $curl_array[$key];

        //set options
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, 300000);

        //method post options
        if ($method === 'post') {
            if ($is_json) {
                $data = json_encode($data);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            }
    
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        //retrieves response headers 
        curl_setopt($curl, CURLOPT_HEADERFUNCTION,
            function($curl, $header) use (&$headers, $key) {
                $len = strlen($header);
                $header = explode(':', $header, 2);
                if (count($header) < 2) return $len;
            
                $headers[$key][strtolower(trim($header[0]))][] = trim($header[1]);
                return $len;
            }
        );

        curl_multi_add_handle($curl_multi, $curl);
    }

    $i = null; 

    do {
        curl_multi_exec($curl_multi, $i);
    } while ($i);

    //retrieves response content
    foreach ($curl_array as $key => $curl) {
        $result = curl_multi_getcontent($curl);
        $results[$key] = json_decode($result, true);
        curl_multi_remove_handle($curl_multi, $curl);
    }

    curl_multi_close($curl_multi);

    //return response
    return array(
        'headers' => $headers,
        'content' => $results
    );
}
