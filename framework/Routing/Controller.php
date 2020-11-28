<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Routing;

use Framework\HTTP\Client;
use Framework\HTTP\Request;
use Framework\HTTP\Redirect;
use Framework\HTTP\Response;
use Framework\Support\Alert;
use Framework\Support\Session;
use Framework\Support\Validator;

/**
 * Main controller class
 */
class Controller
{
    /**
     * @var \Framework\HTTP\Request $request
     */
    public $request;

    /**
     * __construct
     *
     * @param  \Framework\HTTP\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * render view
     *
     * @param  string $view
     * @param  array $data
     * @return void
     */
    public function render(string $view, array $data = [], int $status_code = 200): void
    {
        View::render($view, $data, $status_code);
    }
    
    /**
     * redirect to previous url
     *
     * @return \Framework\HTTP\Redirect
     */
    public function redirectBack(): \Framework\HTTP\Redirect
    {
        return Redirect::back();
    }
    
    /**
     * redirect
     *
     * @param  string $to
     * @param  array|string $params
     * @return \Framework\HTTP\Redirect
     */
    public function redirect(string $to, $params = null): \Framework\HTTP\Redirect
    {
        $url = array_search(
            $to, array_map(
                function ($val) {
                    if (isset($val['name'])) {
                        return $val['name'];
                    }
                },
                Route::$routes
            )
        );

        return !empty($url) ? Redirect::toRoute($to, $params) : Redirect::toUrl($to, $params);
    }
        
    /**
     * send http response
     *
     * @param  mixed $body
     * @param  array $headers
     * @param  int $status_code
     * @return void
     */
    public function response($body, array $headers = [], int $status_code = 200): void
    {
        Response::send($body, $headers, $status_code);
    }
    
    /**
     * send json http response
     *
     * @param  array $body
     * @param  array $headers
     * @param  int $status_code
     * @return void
     */
    public function jsonResponse(array $body, array $headers = [], int $status_code = 200): void
    {
        Response::sendJson($body, $headers, $status_code);
    }
    
    /**
     * send http get request
     *
     * @return \Framework\HTTP\Client
     */
    public function get(array $urls, array $headers = [], ?array $data = null, bool $is_json = false): \Framework\HTTP\Client
    {
        return Client::get($urls, $headers, $data, $is_json);
    }
    
    /**
     * send http post request
     *
     * @return \Framework\HTTP\Client
     */
    public function post(array $urls, array $headers = [], ?array $data = null, bool $is_json = false): \Framework\HTTP\Client
    {
        return Client::post($urls, $headers, $data, $is_json);
    }
    
    /**
     * display alert
     *
     * @param  mixed $message
     * @param  bool $dismiss
     * @return \Framework\Support\Alert
     */
    public function alert($message, bool $dismiss = true): \Framework\Support\Alert
    {
        return Alert::default($message, $dismiss);
    }
    
    /**
     * display toast
     *
     * @param  mixed $message
     * @return \Framework\Support\Alert
     */
    public function toast($message): \Framework\Support\Alert
    {
        return Alert::toast($message);
    }
    
    /**
     * dispaly popup
     *
     * @param  mixed $message
     * @return \Framework\Support\Alert
     */
    public function popup($message): \Framework\Support\Alert
    {
        return Alert::popup($message);
    }
    
    /**
     * create session data
     *
     * @param  string $key
     * @param  mixed $data
     * @return void
     */
    public function session(string $key, $data): void
    {
        Session::create($key, $data);
    }
    
    /**
     * validate inputs and set flash session data
     *
     * @param  array|null $inputs
     * @return \Framework\Routing\Controller
     */
    public function validate(?array $inputs = null, array $rules = [], array $messages = []): self
    {
        $inputs = is_null($inputs) ? $this->request->inputs() : $inputs;
        $validator = Validator::validate($inputs, $rules, $messages);

        if ($validator->fails()) {
            $this->session('errors', $validator->errors());
            $this->session('inputs', $validator->inputs());
        }

        return $this;
    }
}
