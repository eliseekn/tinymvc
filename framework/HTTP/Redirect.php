<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\HTTP;

use Framework\Support\Alert;
use Framework\Support\Session;

/**
 * Handle HTTP redirection
 */
class Redirect
{
    /**
     * url to redirect
     *
     * @var string
     */
    protected static $redirect_url = '';

    /**
     * @var \Framework\Support\Alert
     */
    protected $alert;
    
    /**
     * process redirection
     *
     * @return void
     */
    private function redirect(): void
    {
        redirect_to(self::$redirect_url);
    } 

    /**
     * redirect to url 
     *
     * @param  string $url
     * @param  array|string $params
     * @return \Framework\HTTP\Redirect
     */
    public static function toUrl(string $url, $params = null): self
    {
        $params = is_array($params) ? (empty($params) ? '' : implode('/', $params)) : $params;
        self::$redirect_url = is_null($params) ? $url : $url . '/' . $params;
        return new self();
    }

    /**
     * redirect to route
     *
     * @param  string $name
     * @param  array|string $params
     * @return \Framework\HTTP\Redirect
     */
    public static function toRoute(string $name, $params = null): self
    {
        self::$redirect_url = route_url($name, $params);
        return new self();
    }

    /**
     * go to previous page
     *
     * @return \Framework\HTTP\Redirect
     */
    public static function back(): self
    {
        $history = Session::get('history');

        if (!empty($history)) {
            end($history);
            self::$redirect_url = prev($history);
        }

        return new self();
    }

    /**
     * redirects only
     *
     * @return void
     */
    public function only(): void
    {
        $this->redirect();
    }
    
    /**
     * redirects with session data
     *
     * @param  string $key
     * @param  mixed $data
     * @return void
     */
    public function with(string $key, $data): void
    {
        Session::create($key, $data);
        $this->redirect();
    }
    
    /**
     * redirects with errors
     *
     * @param  array $errors
     * @return \Framework\HTTP\Redirect
     */
    public function withErrors(array $errors): self
    {
        Session::create('errors', $errors);
        return $this;
    }

    /**
     * redirects with inputs
     *
     * @param  array $inputs
     * @return \Framework\HTTP\Redirect
     */
    public function withInputs(array $inputs): self
    {
        Session::create('inputs', $inputs);
        return $this;
    }

    /**
     * redirects with default alert message
     *
     * @param  mixed $messages
     * @param  bool $dismiss
     * @return \Framework\HTTP\Redirect
     */
    public function withAlert($messages, bool $dismiss = true): self
    {
        $this->alert = Alert::default($messages, $dismiss);
        return $this;
    }

    /**
     * redirects with toast message
     *
     * @param  mixed $messages
     * @return \Framework\HTTP\Redirect
     */
    public function withToast($messages): self
    {
        $this->alert = Alert::toast($messages);
        return $this;
    }
    
    /**
     * redirects with popup message
     *
     * @param  mixed $messages
     * @return \Framework\HTTP\Redirect
     */
    public function withPopup($messages): self
    {
        $this->alert = Alert::popup($messages);
        return $this;
    }
        
    /**
     * success
     *
     * @param  string $title
     * @return void
     */
    public function success(string $title = 'Success'): void
    {
        $this->alert->success($title);
        $this->redirect();
    }
        
    /**
     * error
     *
     * @param  string $title
     * @return void
     */
    public function error(string $title = 'Error'): void
    {
        $this->alert->error($title);
        $this->redirect();
    }
        
    /**
     * info
     *
     * @param  string $title
     * @return void
     */
    public function info(string $title = 'Info'): void
    {
        $this->alert->info($title);
        $this->redirect();
    }
        
    /**
     * warning
     *
     * @param  string $title
     * @return void
     */
    public function warning(string $title = 'Warning'): void
    {
        $this->alert->warning($title);
        $this->redirect();
    }
}
