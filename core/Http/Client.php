<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http;

use Exception;

/**
 * Send HTTP requests
 */
class Client
{
    /**
     * @var array
     */
    protected static $response = [];

    /**
     * send asynchronous HTTP request using curl
     *
     * @param  string $method
     * @param  mixed $urls
     * @param  array $headers
     * @param  array|null $data
     * @param  bool $json
     * @return \Core\Http\Client|void
     * @link   https://niraeth.com/php-quick-function-for-asynchronous-multi-curl/
     *         https://stackoverflow.com/questions/9183178/can-php-curl-retrieve-response-headers-and-body-in-a-single-request
     *         https://www.codexworld.com/post-receive-json-data-using-php-curl/
     *         https://stackoverflow.com/questions/13420952/php-curl-delete-request
     * 
     * @throws Exception
     */
    public static function send(string $method, $urls, array $headers = [], ?array $data = null, bool $json = false) 
    {
        $response_headers = [];
        $response = [];
        $status_code = [];
        $curl_array = [];
        $curl_multi = curl_multi_init(); //init multiple curl processing
        
        if (!is_array($urls)) {
            if (!is_string($urls)) {
                throw new Exception('Invalid url format.');
            }

            $urls = [$urls];
        }

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
                if ($json) {
                    $data = json_encode($data);
                    $headers[] = 'Content-Type: application/json';
                }
        
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }

            //set headers
            if (!empty($headers)) {
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            }
            
            //retrieves response headers 
            curl_setopt(
                $curl,
                CURLOPT_HEADERFUNCTION,
                function ($curl, $header) use (&$response_headers, $key) {
                    $len = strlen($header);
                    $header = explode(':', $header, 2);
                    if (count($header) < 2) return $len;

                    $response_headers[$key][strtolower(trim($header[0]))][] = trim($header[1]);
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
            $response[$key] = curl_multi_getcontent($curl);
            $status_code[$key] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_multi_remove_handle($curl_multi, $curl);
        }

        curl_multi_close($curl_multi);

        self::$response['headers'] = $response_headers;
        self::$response['body'] = $response;
        self::$response['status_code'] = $status_code;

        return new self();
    }

    /**
     * send GET request
     *
     * @param  mixed $urls
     * @param  array $headers
     * @return \Core\Http\Client
     */
    public static function get($urls, array $headers = []): self {
        return self::send('GET', $urls, $headers);
    }
    
    /**
     * send POST request
     *
     * @param  mixed $urls
     * @param  array $headers
     * @param  array|null $data
     * @param  bool $json
     * @return \Core\Http\Client
     */
    public static function post($urls, array $headers = [], ?array $data = null, bool $json = false): self {
        return self::send('POST', $urls, $headers, $data, $json);
    }

    /**
     * send PUT request
     *
     * @param  mixed $urls
     * @param  array $headers
     * @param  array|null $data
     * @param  bool $json
     * @return \Core\Http\Client
     */
    public static function put($urls, array $headers = [], ?array $data = null, bool $json = false): self {
        return self::send('PUT', $urls, $headers, $data, $json);
    }

    /**
     * send DELETE request
     *
     * @param  mixed $urls
     * @param  array $headers
     * @param  array|null $data
     * @param  bool $json send data in json
     * @return \Core\Http\Client
     */
    public static function delete($urls, array $headers = [], ?array $data = null, bool $json = false): self {
        return self::send('DELETE', $urls, $headers, $data, $json);
    }

    /**
     * send OPTIONS request
     *
     * @param  mixed $urls
     * @param  array $headers
     * @param  array|null $data
     * @param  bool $json
     * @return \Core\Http\Client
     */
    public static function options($urls, array $headers = [], ?array $data = null, bool $json = false): self {
        return self::send('OPTIONS', $urls, $headers, $data, $json);
    }

    /**
     * send PATCH request
     *
     * @param  mixed $urls
     * @param  array $headers
     * @param  array|null $data
     * @param  bool $json
     * @return \Core\Http\Client
     */
    public static function patch($urls, array $headers = [], ?array $data = null, bool $json = false): self {
        return self::send('PATCH', $urls, $headers, $data, $json);
    }

    /**
     * retrieves response headers
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return self::$response['headers'];
    }

    /**
     * retrieves response body
     *
     * @return array
     */
    public function getBody(): array
    {
        return self::$response['body'];
    }

    /**
     * retrieves status code
     *
     * @return array
     */
    public function getStatusCode(): array
    {
        return self::$response['status_code'];
    }
}
