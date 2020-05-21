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
 * Curl HTTP requests
 */

/**
 * send asynchronous HTTP request using PHP curl function
 *
 * @param  string $method request method
 * @param  array $urls urls to connect
 * @param  array $data data to send
 * @param  bool $json_data send json data or not
 * @return array returns headers and body reponse
 * 
 * @link   https://niraeth.com/php-quick-function-for-asynchronous-multi-curl/
 *         https://stackoverflow.com/questions/9183178/can-php-curl-retrieve-response-headers-and-body-in-a-single-request
 *         https://www.codexworld.com/post-receive-json-data-using-php-curl/
 *         https://stackoverflow.com/questions/13420952/php-curl-delete-request
 */
function curl(string $method, array $urls, ?array $data = null, bool $json_data = false): array
{
    $results = [];
    $headers = [];
    $curl_array = [];
    $curl_multi = curl_multi_init(); //init multiple curl processing

    foreach ($urls as $key => $url) {
        $curl_array[$key] = curl_init();
        $curl = $curl_array[$key];

        //set options
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, 300000);

        //set method
        if (strtoupper($method) !== 'GET') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        }
        
        //set data
        if (!is_null($data)) {
            if ($json_data) {
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                $data = json_encode($data);
            }
    
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        
        //retrieves response headers 
        curl_setopt(
            $curl,
            CURLOPT_HEADERFUNCTION,
            function ($curl, $header) use (&$headers, $key) {
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

    //retrieves response
    foreach ($curl_array as $key => $curl) {
        $results[$key] = curl_multi_getcontent($curl);
        curl_multi_remove_handle($curl_multi, $curl);
    }

    curl_multi_close($curl_multi);

    //return response
    return [
        'headers' => $headers,
        'body' => $results
    ];
}